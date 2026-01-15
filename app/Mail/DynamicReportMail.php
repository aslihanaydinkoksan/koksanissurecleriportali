<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicReportMail extends Mailable
{
    use Queueable, SerializesModels;

    // Bu iki değişken public olduğu için Blade içinde doğrudan kullanılabilir
    public $reportName;
    public $fileFormat;

    protected $filePath;
    protected $fileName;

    public function __construct($reportName, $filePath, $fileName, $fileFormat)
    {
        $this->reportName = $reportName;
        $this->fileFormat = $fileFormat;
        $this->fileName = $fileName;
    }

    public function build()
    {
        $mimeType = $this->fileFormat === 'pdf'
            ? 'application/pdf'
            : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        return $this->subject('📊 Rapor: ' . $this->reportName)
            ->markdown('emails.reports.scheduled')
            ->attach($this->filePath, [
                    'as' => $this->fileName,
                    'mime' => $mimeType,
                ]);
    }
}