<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; // <--- 1. Event desteği eklendi
use Maatwebsite\Excel\Events\AfterSheet;  // <--- 2. AfterSheet olayı eklendi
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DynamicReportSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    private $title;
    private $data;
    private $headers;

    public function __construct(string $title, $data, array $headers)
    {
        $this->title = $title;
        $this->data = $data;
        $this->headers = $headers;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * Başlık satırını Köksan Kurumsal Koyu Mavi yapar.
     */
    public function styles(Worksheet $sheet)
    {
        // Tüm veri alanını kapsayacak şekilde (Örn: A1:Z100) wrap text ve dikey hizalama uygulayalım
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // PDF'de karakterlerin düzelmesi için tüm tabloya fontu dayatıyoruz
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('DejaVu Sans');
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setSize(9); // PDF'de taşma olmaması için 9 idealdir

        return [
            // Başlık Satırı (Koyu Mavi)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'name' => 'DejaVu Sans',
                    'size' => 10
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1D3557']
                ],
            ],
        ];
    }

    /**
     * Excel dosyası oluşturulduktan sonra tetiklenir.
     * Portal linklerini gerçek tıklanabilir köprülere dönüştürür.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 1. "Portal Linki" sütununun hangi harfte olduğunu bulalım
                $columnIndex = array_search('Portal Linki', $this->headers);

                // Eğer bu sekmede "Portal Linki" sütunu varsa işleme başla
                if ($columnIndex !== false) {
                    // Sayısal index'i Excel harfine çevir (Örn: 11 -> L)
                    $columnLetter = Coordinate::stringFromColumnIndex($columnIndex + 1);

                    // Veri satırı sayısını al (+1 başlık satırı için)
                    $rowCount = count($this->data) + 1;

                    // 2. Satırdan başlayarak (1. satır başlıktır) tüm hücreleri gez
                    for ($i = 2; $i <= $rowCount; $i++) {
                        $cellCoordinate = $columnLetter . $i;
                        $cell = $event->sheet->getDelegate()->getCell($cellCoordinate);
                        $url = $cell->getValue();

                        // Eğer hücrede geçerli bir URL varsa köprüye dönüştür
                        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                            $cell->getHyperlink()->setUrl($url);

                            // 3. Kurumsal Hyperlink Stili (Mavi ve Altı Çizili)
                            $event->sheet->getDelegate()->getStyle($cellCoordinate)->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => true
                                ]
                            ]);
                        }
                    }
                }
            },
        ];
    }
}