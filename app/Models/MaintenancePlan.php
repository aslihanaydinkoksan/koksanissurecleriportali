<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenancePlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'planned_start_date' => 'datetime',
        'planned_end_date' => 'datetime',
        'actual_start_date' => 'datetime',
        'actual_end_date' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }
    public function asset()
    {
        return $this->belongsTo(MaintenanceAsset::class, 'maintenance_asset_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function files()
    {
        return $this->hasMany(MaintenanceFile::class);
    }
    public function timeEntries()
    {
        return $this->hasMany(MaintenanceTimeEntry::class);
    }
    public function logs()
    {
        return $this->hasMany(MaintenanceActivityLog::class);
    }

    // Yardımcı: Sayaç açık mı?
    public function isTimerActive()
    {
        return $this->timeEntries()->whereNull('end_time')->exists();
    }
    public function getStatusLabelAttribute()
    {
        // Önce standart durumları kontrol et
        if ($this->status == 'pending')
            return 'Bekliyor';
        if ($this->status == 'completed')
            return 'Tamamlandı';
        if ($this->status == 'cancelled')
            return 'İptal Edildi';

        // Eğer durum 'in_progress' ise sayaca bak:
        if ($this->status == 'in_progress') {
            // Sayaç aktifse "Çalışılıyor", değilse "Duraklatıldı"
            return $this->isTimerActive() ? 'Çalışma Sürüyor' : 'Duraklatıldı';
        }

        return 'Bilinmiyor';
    }

    // 2. Durumun Rengini getiren özellik ($plan->status_color)
    public function getStatusColorAttribute()
    {
        if ($this->status == 'pending')
            return 'warning';      // Sarı
        if ($this->status == 'completed')
            return 'success';    // Yeşil
        if ($this->status == 'cancelled')
            return 'danger';     // Kırmızı

        if ($this->status == 'in_progress') {
            // Aktif çalışılıyorsa Mavi (Primary), durduysa Turkuaz/Gri (Info veya Secondary)
            return $this->isTimerActive() ? 'primary' : 'info text-dark';
        }

        return 'secondary';
    }
    // Tamamlanmış (End time'ı olan) tüm kayıtların toplam süresi
    public function getPreviousDurationMinutesAttribute()
    {
        return $this->timeEntries()->whereNotNull('end_time')->sum('duration_minutes');
    }
    public function getPriorityBadgeAttribute()
    {
        $val = strtolower($this->priority ?? '');
        $definitions = [
            'critical' => ['text' => 'Kritik', 'class' => 'bg-danger text-white'],
            'high' => ['text' => 'Yüksek', 'class' => 'bg-warning text-dark'],
            'normal' => ['text' => 'Normal', 'class' => 'bg-secondary text-white'],
            'low' => ['text' => 'Düşük', 'class' => 'bg-success text-white'],
        ];
        $data = $definitions[$val] ?? ['text' => 'Normal', 'class' => 'bg-secondary text-white'];

        return [
            'text' => $data['text'],
            'class' => $data['class'],
            'is_badge' => true
        ];
    }
    public function getStatusBadgeAttribute()
    {
        $color = $this->status_color;
        $textColor = str_contains($color, 'text-') ? '' : 'text-white';

        return [
            'text' => $this->status_label, // Senin mevcut status_label accessor'ını kullanır
            'class' => "bg-{$color} {$textColor}", // Örn: "bg-primary text-white"
            'is_badge' => true
        ];
    }
}
