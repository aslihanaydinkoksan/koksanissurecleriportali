<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class CsvExporter
{
    /**
     * Herhangi bir sorguyu CSV olarak dışa aktarır.
     *
     * @param Builder $query Veritabanı sorgusu (get() yapılmamış hali)
     * @param array $headers CSV'nin ilk satırında yazacak başlıklar
     * @param callable $rowMapper Her bir satırın nasıl işleneceğini belirleyen fonksiyon
     * @param string $fileName İndirilecek dosyanın adı
     */
    public static function streamDownload(Builder $query, array $headers, callable $rowMapper, string $fileName)
    {
        // HTTP Başlıkları
        $httpHeaders = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Stream Callback Fonksiyonu
        $callback = function () use ($query, $headers, $rowMapper) {
            $file = fopen('php://output', 'w');

            // Excel için Türkçe karakter desteği (BOM)
            fputs($file, "\xEF\xBB\xBF");

            // 1. Satır: Başlıkları yaz
            fputcsv($file, $headers, ';');

            // Veriyi parça parça çek (Memory dostu cursor)
            foreach ($query->cursor() as $record) {
                // Controller'dan gelen formatlama fonksiyonunu çalıştır
                $row = $rowMapper($record);

                // Satırı dosyaya yaz
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $httpHeaders);
    }
}