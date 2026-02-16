<?php

namespace App\Http\Controllers;

use App\Services\Contracts\AIServiceInterface;
use App\Services\AIContextService; // Yeni servisimizi ekledik
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected AIServiceInterface $aiService;
    protected AIContextService $contextService;

    // Dependency Injection ile iki servisi de alıyoruz
    public function __construct(AIServiceInterface $aiService, AIContextService $contextService)
    {
        $this->aiService = $aiService;
        $this->contextService = $contextService;
    }

    public function ask(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        // 1. Tüm modüllerden veriyi tek satırda çekiyoruz! (Clean Code)
        $context = $this->contextService->getAggregatedContext();

        // 2. Soruyu ve zenginleştirilmiş veriyi AI'a gönderiyoruz
        $answer = $this->aiService->ask($request->message, $context);

        return response()->json(['answer' => $answer]);
    }
}