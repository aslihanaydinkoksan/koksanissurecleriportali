<?php

namespace App\Services\Contracts;

interface AIServiceInterface
{
    /**
     * AI modeline soru sorar ve yanıt döner.
     */
    public function ask(string $question, array $context = []): string;
}