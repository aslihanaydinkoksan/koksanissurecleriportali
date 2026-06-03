<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Loggable;
use App\Traits\HasBusinessUnit;
use App\Models\CustomerReturn;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property int|null $customer_machine_id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperComplaint
 */
class Complaint extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Loggable, HasBusinessUnit , SoftDeletes;
    protected $fillable = ['customer_id', 'user_id', 'customer_machine_id', 'title', 'description', 'status', 'remote_creator_name'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    // Bir şikayete bağlı iadeler
    public function returns()
    {
        return $this->hasMany(CustomerReturn::class);
    }

    /**
     * Duruma göre renk döndüren Accessor
     * Keyword-based (anahtar kelime tabanlı) arama ile daha esnek hale getirildi.
     */
    public function getDurumRengiAttribute()
    {
        $status = mb_strtolower($this->status, 'UTF-8');
        
        // Mavi: Yeni Başlayanlar
        if (str_contains($status, 'yeni') || str_contains($status, 'açık') || str_contains($status, 'open')) {
            return 'blue';
        }
        
        // Turuncu: Devam Edenler ve Onay Bekleyenler (IAA Turuncusu)
        // 'isl', 'işl' ve 'progress' gibi köklerden yakalayarak daha garanti hale getirildi
        if (str_contains($status, 'işlem') || str_contains($status, 'islem') || 
            str_contains($status, 'progres') || str_contains($status, 'progress') || 
            str_contains($status, 'devam') || str_contains($status, 'atandı') || 
            str_contains($status, 'inceleniyor') || str_contains($status, 'bekliyor') || 
            str_contains($status, 'revize ediliyor')) {
            return 'orange';
        }
        
        // Yeşil: Tamamlananlar
        if (str_contains($status, 'çözüm') || str_contains($status, 'cozum') || 
            str_contains($status, 'kapat') || str_contains($status, 'tamam') || 
            str_contains($status, 'resolved') || str_contains($status, 'kapali')) {
            return 'green';
        }
        
        // Kırmızı: İptaller ve Redler
        if (str_contains($status, 'iptal') || str_contains($status, 'red') || 
            str_contains($status, 'revize')) {
            return 'red';
        }

        // Özel Durumlar
        if (str_contains($status, 'bölüm')) return 'purple';
        if (str_contains($status, 'direktör')) return 'pink';
        if (str_contains($status, 'kalite')) return 'purple';
        if (str_contains($status, 'yönetim') || str_contains($status, 'superadmin')) return 'indigo';
        
        return 'gray';
    }

    /**
     * Durum metnini temizleyip döndürür
     */
    public function getCleanStatusAttribute()
    {
        $status = mb_strtolower($this->status, 'UTF-8');
        
        // In Progress -> İşlemde dönüşümü için kök kontrolü
        if (str_contains($status, 'progress')) return 'İşlemde';
        if ($status == 'open') return 'Açık';
        if ($status == 'resolved') return 'Çözüldü';
        
        return mb_convert_case($this->status, MB_CASE_TITLE, "UTF-8");
    }
}
