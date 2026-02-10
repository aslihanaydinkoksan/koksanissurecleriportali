<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DynamicReportExport implements WithMultipleSheets
{
    protected $data;
    protected $headers;

    /**
     * Artık constructor daha esnek: mixed $data (Array veya Collection olabilir)
     */
    public function __construct($data, $headers)
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * Maatwebsite/Excel bu metodu çağırarak sekmeleri oluşturur.
     */
    public function sheets(): array
    {
        $sheets = [];
        $allData = $this->data->all();

        if (is_array($allData) && !isset($allData[0])) {
            foreach ($allData as $sheetName => $sheetData) {
                $sheets[] = new DynamicReportSheet(
                    $sheetName,
                    $sheetData,
                    $this->headers[$sheetName] ?? []
                );
            }
        } else {
            // Tek tablolu klasik raporlar için fallback
            $sheets[] = new DynamicReportSheet('Rapor', $this->data, $this->headers);
        }

        return $sheets;
    }
}