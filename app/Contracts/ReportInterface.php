<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReportInterface
{
    public function getName(): string;
    public function getData(string $frequency): Collection;
    public function getHeaders(): array;
}