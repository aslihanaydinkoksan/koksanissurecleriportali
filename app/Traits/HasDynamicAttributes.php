<?php

namespace App\Traits;

use App\Models\CustomFieldDefinition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

trait HasDynamicAttributes
{

    // Bu model için tanımlı dinamik alanları getir
    public static function getCustomFields()
    {
        // 1. Aktif İş Birimini Algıla (Session öncelikli, yoksa User)
        $activeBusinessUnitId = Session::get('active_unit_id')
            ?? (Auth::check() ? Auth::user()->business_unit_id : null);

        // 2. Sorguyu Oluştur
        return CustomFieldDefinition::where('model_scope', self::class)
            ->where('is_active', true)
            // [KRİTİK GÜNCELLEME]: Sadece genel veya bu birime özel alanları getir
            ->where(function ($query) use ($activeBusinessUnitId) {
                $query->whereNull('business_unit_id'); // Tüm birimler için olanlar
    
                if ($activeBusinessUnitId) {
                    $query->orWhere('business_unit_id', $activeBusinessUnitId); // Sadece bu birim için olanlar
                }
            })
            ->orderBy('order')
            ->get();
    }

    // Validasyon kurallarını dinamik üret
    public static function getDynamicValidationRules()
    {
        $fields = self::getCustomFields();
        $rules = [];

        foreach ($fields as $field) {
            // Formdan gelen veri 'extras.alan_adi' şeklinde olacak
            $ruleKey = 'extras.' . $field->key;

            // 1. Temel Zorunluluk Kuralı
            $fieldRules = $field->is_required ? ['required'] : ['nullable'];

            // 2. Tip Bazlı Kurallar
            switch ($field->type) {
                // METİN TİPLERİ
                case 'text':            // Kısa metin
                case 'string':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:255';
                    break;

                case 'textarea':        // Uzun metin
                case 'editor':          // Rich text editor
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:10000'; // Makul bir üst sınır
                    break;

                case 'password':        // Olur da şifreli alan gerekirse
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'min:6';
                    break;

                // SAYISAL TİPLER
                case 'number':          // Hem tam sayı hem ondalık olabilir
                case 'decimal':
                case 'money':           // Para birimi
                    $fieldRules[] = 'numeric';
                    break;

                case 'integer':         // Sadece tam sayı (örn: Adet)
                    $fieldRules[] = 'integer';
                    break;

                // İLETİŞİM & WEB
                case 'email':
                    $fieldRules[] = 'email';
                    $fieldRules[] = 'max:255';
                    break;

                case 'url':             // Web sitesi linki
                case 'website':
                    $fieldRules[] = 'url';
                    $fieldRules[] = 'max:255';
                    break;

                case 'phone':           // Telefon (Regex opsiyonel, genelde string tutulur)
                case 'tel':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:20';
                    // İstersen regex: $fieldRules[] = 'regex:/^([0-9\s\-\+\(\)]*)$/';
                    break;

                // TARİH & SAAT
                case 'date':
                    $fieldRules[] = 'date';
                    break;

                case 'datetime':
                case 'datetime-local':
                    $fieldRules[] = 'date'; // Laravel datetime'ı date olarak kabul eder
                    break;

                case 'time':
                    $fieldRules[] = 'date_format:H:i'; // Saat formatı (Örn: 14:30)
                    break;

                // SEÇİM KUTULARI (Kritik Kısım)
                case 'select':          // Tekli seçim
                case 'radio':           // Radio buton grubu
                    // Adminin tanımladığı seçenekler (options) dışında bir veri gelemez!
                    if (!empty($field->options) && is_array($field->options)) {
                        $fieldRules[] = Rule::in($field->options);
                    }
                    break;

                case 'multi_select':    // Çoklu seçim (Array döner)
                case 'checkbox_group':
                    $fieldRules[] = 'array';
                    // Array içindeki her bir eleman, tanımlı options içinde olmalı
                    if (!empty($field->options) && is_array($field->options)) {
                        // extras.key.* kuralı ekliyoruz
                        $rules[$ruleKey . '.*'] = Rule::in($field->options);
                    }
                    break;

                case 'boolean':         // Tekli Checkbox (Onay kutusu)
                case 'checkbox':
                    $fieldRules[] = 'boolean'; // true, false, 1, 0, "1", "0" kabul eder
                    break;

                // DOSYA (Opsiyonel - JSON içinde dosya yolu tutulacaksa)
                case 'file':
                    // Not: Dosya yükleme mantığı Controller'da ayrıca ele alınmalı,
                    // buradaki validasyon sadece dosya tipi kontrolü içindir.
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'max:10240'; // 10MB
                    break;
            }

            // Ana kural setini diziye ata
            $rules[$ruleKey] = $fieldRules;
        }

        return $rules;
    }
}