{{-- prettier-ignore-start --}}
@component('mail::message')
# {{ $reportName }}

KÖKSAN Takvim periyodik raporunuz ektedir.

@component('mail::panel')
**Rapor Özeti**
* **Adı:** {{ $reportName }}
* **Tarih:** {{ now()->format('d.m.Y') }}
* **Saat:** {{ now()->format('H:i') }}
* **Format:** {{ $fileFormat === 'pdf' ? 'PDF' : 'Excel' }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
Portala Git
@endcomponent

Teşekkürler,
**{{ config('app.name') }}**
@endcomponent
{{-- prettier-ignore-end --}}
