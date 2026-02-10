<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasBusinessUnit;
use App\Traits\Loggable;
use App\Traits\HasDynamicAttributes;
use App\Traits\HasKanban;
class MaintenancePlan extends Model
{
    use HasFactory, SoftDeletes, HasBusinessUnit, Loggable, HasDynamicAttributes, HasKanban;

    protected $guarded = [];

    protected $casts = [
        'planned_start_date' => 'datetime',
        'planned_end_date' => 'datetime',
        'actual_start_date' => 'datetime',
        'actual_end_date' => 'datetime',
        'extras' => 'array',
    ];

    // --- EKLENEN KRİTİK METOD BAŞLANGICI ---
    /**
     * Kullanıcı yetkisine göre filtreleme kapsamı (Scope).
     * Service katmanındaki '::forUser($user)' çağrısını karşılar.
     */
    public function scopeForUser($query, $user)
    {
        // 1. Admin, Yönetici veya Global yetkisi olanlar her şeyi görebilir
        if ($user->hasRole(['admin', 'yonetici']) || $user->can('view_all_business_units')) {
            return $query;
        }

        // 2. Normal kullanıcılar için:
        // Şimdilik sorguyu olduğu gibi döndürüyoruz çünkü Controller/Service katmanında
        // zaten 'active_unit_id' (Fabrika) filtresi uygulanıyor.
        // Eğer ileride "Sadece kendi oluşturduğu planları görsün" istersen burayı açabilirsin:
        // return $query->where('user_id', $user->id);

        return $query;
    }
    // --- EKLENEN KRİTİK METOD BİTİŞİ ---

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

    // Yardımcı: Sayaç açık mı? (Sadece giriş yapan kullanıcı için kontrol etmeli)
    public function isTimerActive()
    {
        return $this->timeEntries()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereNull('end_time')
            ->exists();
    }

    // 1. Durumun Ekranda Görünen Adı
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending_approval' => 'Onay Bekliyor',
            'pending' => 'Bekliyor',
            'in_progress' => $this->isTimerActive() ? 'Çalışma Sürüyor' : 'Duraklatıldı',
            'completed' => 'Tamamlandı',
            'cancelled' => 'İptal Edildi',
            default => 'Bilinmiyor',
        };
    }

    // 2. Durumun Rengi (Badge Fonksiyonun bunu kullanıyor)
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending_approval' => 'warning text-dark',
            'pending' => 'warning text-dark',
            'completed' => 'success',
            'cancelled' => 'danger',
            'in_progress' => $this->isTimerActive() ? 'primary' : 'info text-dark',
            default => 'secondary',
        };
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
            'text' => $this->status_label,
            'class' => "bg-{$color} {$textColor}",
            'is_badge' => true
        ];
    }
    // Kanban kartlarıyla polimorfik ilişki (Bir işin birden fazla kartı olabilir - her kullanıcı için bir tane)
    public function kanbanCard()
    {
        return $this->morphMany(KanbanCard::class, 'model');
    }
}