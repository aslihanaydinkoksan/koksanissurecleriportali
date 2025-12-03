<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\Loggable;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $bookable_id 
 * @property string $bookable_type 
 * @property int $user_id
 * @property string $type
 * @property string|null $provider_name
 * @property string|null $confirmation_code
 * @property string|null $cost
 * @property string|null $start_datetime
 * @property string|null $end_datetime
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Model|\Eloquent $bookable // DEĞİŞTİ: Artık travel değil, dinamik bookable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperBooking
 */
class Booking extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Loggable;

    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'user_id',
        'type',
        'provider_name',
        'confirmation_code',
        'cost',
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'notes',
    ];

    /**
     * Polimorfik İlişki Tanımı
     * Bu rezervasyon bir Travel'a DA ait olabilir, bir Event'e DE.
     * $booking->bookable dediğinde otomatik olarak hangisiyse onu getirecek.
     */
    public function bookable()
    {
        return $this->morphTo();
    }

    /**
     * Geriye Dönük Uyumluluk (Opsiyonel)
     * Eğer kodunun başka yerlerinde hala $booking->travel diye çağırdığın yerler varsa
     * patlamasın diye bu kısayolu bırakabilirsin. Ama doğrusu artık ->bookable kullanmaktır.
     */
    public function getTravelAttribute()
    {
        // Eğer bağlı olduğu model Travel ise onu döndür, değilse null dön.
        if ($this->bookable_type === 'App\Models\Travel' || $this->bookable_type === 'App\Models\Event') {
            return $this->bookable;
        }
        return null;
    }

    // Bu rezervasyonu ekleyen kullanıcı
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Dosyalar İlişkisi
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
    /**
     * Rezervasyon düzenlenebilir durumda mı?
     * Kural: Başlangıç tarihine 24 saatten az kaldıysa (veya tarih geçtiyse) düzenlenemez.
     */
    public function getIsEditableAttribute(): bool
    {
        // Başlangıç tarihi boşsa düzenlenebilir (Hata olmaması için)
        if (!$this->start_datetime)
            return true;

        $start = \Carbon\Carbon::parse($this->start_datetime);
        $limit = now()->addHours(24);

        // Eğer başlangıç tarihi, (şu an + 24 saat)'ten küçükse
        // Yani: 24 saatten az kaldıysa veya zamanı geçtiyse -> FALSE dön
        if ($start->lt($limit)) {
            return false;
        }

        return true;
    }
}