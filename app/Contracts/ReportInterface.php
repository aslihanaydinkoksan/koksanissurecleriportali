<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ReportInterface
{
    /**
     * Dropdown listesinde görünecek genel rapor türü adı.
     */
    public function getName(): string;

    /**
     * Veriyi çeken ana metod. 
     * @param string $frequency (daily, weekly, monthly)
     */
    public function getData(string $frequency): Collection;

    /**
     * Excel/PDF sütun başlıkları.
     */
    public function getHeaders(): array;
}