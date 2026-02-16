<?php

namespace App\Services;

use App\Services\Contracts\AIServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService implements AIServiceInterface
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('ai.api_key');
        $this->model = config('ai.model');
    }
    public function ask(string $question, array $context = []): string
    {
        $apiKey = config('ai.api_key');

        try {
            // 1. Önce Google'ın bu anahtar için izin verdiği modelleri alalım
            $listUrl = "https://generativelanguage.googleapis.com/v1/models?key={$apiKey}";
            $modelsResponse = Http::withoutVerifying()->get($listUrl);

            $activeModel = 'models/gemini-1.5-flash'; // Default fallback

            if ($modelsResponse->successful()) {
                $availableModels = $modelsResponse->json('models');
                // Listeden 'generateContent' destekleyen ilk flash veya pro modeli seç
                foreach ($availableModels as $m) {
                    if (str_contains($m['name'], 'flash') || str_contains($m['name'], 'pro')) {
                        if (in_array('generateContent', $m['supportedGenerationMethods'])) {
                            $activeModel = $m['name'];
                            break;
                        }
                    }
                }
            }

            // 2. Bulunan (veya fallback) model ile asıl isteği v1 sürümü üzerinden yapalım
            $url = "https://generativelanguage.googleapis.com/v1/{$activeModel}:generateContent?key={$apiKey}";

            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $this->buildSystemPrompt($context) . "\n\nSoru: " . $question]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text') ?? "Yanıt içeriği boş.";
            }

            Log::error("Gemini API Hatası: " . $response->body());
            return "Sistem şu an yanıt veremiyor. (Model: {$activeModel})";
        } catch (\Exception $e) {
            Log::error('Gemini Discovery Exception: ' . $e->getMessage());
            return "Bağlantı hatası: " . $e->getMessage();
        }
    }

    private function buildSystemPrompt(array $context): string
    {
        $prompt = config('ai.system_prompt');
        $prompt .= "\n\n--- [SİSTEM ZAMANI: " . now()->format('d.m.Y H:i') . "] ---\n";

        if (!empty($context)) {
            $prompt .= "\n--- [KULLANICI VERİLERİ] ---\n";
            $prompt .= "Kullanıcı: " . ($context['user_name'] ?? 'Misafir') . " (" . ($context['roles'] ?? '') . ")\n";

            // Helper fonksiyon ile kod tekrarını önleyelim (DRY Prensibi)
            $appendList = function ($title, $items) use (&$prompt) {
                if (!empty($items)) {
                    $prompt .= "\n$title:\n";
                    foreach ($items as $item) {
                        $prompt .= "- $item\n";
                    }
                }
            };

            $appendList("🗓️ Yaklaşan Etkinlikler", $context['events'] ?? []);
            $appendList("📝 Yapılacak Görevler", $context['todos'] ?? []);
            $appendList("🚚 Sevkiyatlar (Lojistik)", $context['shipments'] ?? []);
            $appendList("🔧 Bakım Planları", $context['maintenances'] ?? []);
            $appendList("🏭 Üretim Planları", $context['productions'] ?? []);
            $appendList("✈️ Seyahat/Rezervasyonlar", $context['bookings'] ?? []);
        }
        
        return $prompt;
    }
}
