<?php

namespace App\Services;

class ReportRegistryService
{
    public static function getAvailableReports(): array
    {
        $reports = [];
        $path = app_path('Reports');

        // Klasördeki tüm PHP dosyalarını tara
        foreach (glob($path . '/*.php') as $filename) {
            $className = 'App\\Reports\\' . basename($filename, '.php');
            if (class_exists($className)) {
                $instance = new $className();
                // Interface'i uygulayıp uygulamadığını kontrol et (SOLID)
                if ($instance instanceof \App\Contracts\ReportInterface) {
                    $reports[$className] = $instance->getName();
                }
            }
        }
        return $reports;
    }
}