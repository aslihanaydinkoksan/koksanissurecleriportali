<?php

namespace App\Services\Contracts;

interface AIServiceInterface
{
    public function ask(string $question, array $context = []): string;
}