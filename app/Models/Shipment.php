<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

/**
 * App\Models\Shipment
 *
 * @property int $id
 * @property string|null $shipment_type
 * @property int $user_id
 * @property bool $is_important
 * @property string $arac_tipi
 * @property string|null $plaka
 * @property string|null $dorse_plakasi
 * @property string|null $sofor_adi
 * @property string|null $imo_numarasi
 * @property string|null $gemi_adi
 * @property string|null $kalkis_limani
 * @property string|null $varis_limani
 * @property string|null $kalkis_noktasi
 * @property string|null $varis_noktasi
 * @property \Illuminate\Support\Carbon|null $onaylanma_tarihi
 * @property int|null $onaylayan_user_id
 * @property string $kargo_icerigi
 * @property string $kargo_tipi
 * @property string $kargo_miktari
 * @property \Illuminate\Support\Carbon $cikis_tarihi
 * @property \Illuminate\Support\Carbon $tahmini_varis_tarihi
 * @property mixed|null $ekstra_bilgiler
 * @property string|null $aciklamalar
 * @property string|null $dosya_yolu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $onaylayanKullanici
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereAciklamalar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereAracTipi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereCikisTarihi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDorsePlakasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDosyaYolu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereEkstraBilgiler($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereGemiAdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereImoNumarasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereKalkisLimani($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereKalkisNoktasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereKargoIcerigi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereKargoMiktari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereKargoTipi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereOnaylanmaTarihi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereOnaylayanUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment wherePlaka($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereSoforAdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereTahminiVarisTarihi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereVarisLimani($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereVarisNoktasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperShipment
 */
class Shipment extends Model
{
    use HasFactory, SoftDeletes, Loggable;

    protected $fillable = [
        'user_id',
        'shipment_type',
        'arac_tipi',
        'plaka',
        'dorse_plakasi',
        'sofor_adi',
        'imo_numarasi',
        'gemi_adi',
        'kalkis_limani',
        'varis_limani',
        'kalkis_noktasi',
        'varis_noktasi',
        'kargo_icerigi',
        'kargo_tipi',
        'kargo_miktari',
        'cikis_tarihi',
        'tahmini_varis_tarihi',
        'onaylanma_tarihi',
        'onaylayan_user_id',
        'ekstra_bilgiler', // Bu alanı artık kullanmayabiliriz veya başka amaçlar için tutabiliriz
        'aciklamalar',
        'dosya_yolu',
        'is_important',
    ];
    protected $casts = [
        'cikis_tarihi' => 'datetime',
        'tahmini_varis_tarihi' => 'datetime',
        'onaylanma_tarihi' => 'datetime',
        'is_important' => 'boolean',
    ];

    public function onaylayanKullanici(): BelongsTo
    {
        return $this->belongsTo(User::class, 'onaylayan_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
