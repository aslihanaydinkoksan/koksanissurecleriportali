<?php

namespace App\Observers;

use App\Models\CustomerActivity;
use App\Models\Event;
use App\Models\CustomerVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerActivityObserver
{
    /**
     * Takvime ve Ziyaret sekmesine yansıması gereken aktivite tipleri.
     */
    private const SYNCABLE_TYPES = ['toplantı', 'meeting', 'ziyaret', 'visit'];

    public function created(CustomerActivity $activity): void
    {
        // Eğer aktivite tipi takvime/ziyarete uygun değilse işlem yapma
        if (! $this->shouldSyncToCalendar($activity->type)) {
            return;
        }

        $this->createEventFromActivity($activity);
    }

    public function updated(CustomerActivity $activity): void
    {
        try {
            $event = Event::whereJsonContains('extras->linked_activity_id', $activity->id)->first();
            $shouldSync = $this->shouldSyncToCalendar($activity->type);

            if ($shouldSync) {
                if ($event) {
                    // Tipi hala uygun, güncellemeleri yansıt
                    $this->updateEventFromActivity($event, $activity);
                } else {
                    // Eskiden telefondu, şimdi ziyarete çevrildi -> Yeni oluştur
                    $this->createEventFromActivity($activity);
                }
            } else {
                if ($event) {
                    // Eskiden ziyaretti, şimdi telefona çevrildi -> Takvimden ve Ziyaretlerden Sil
                    CustomerVisit::where('event_id', $event->id)->delete();
                    $event->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error("CustomerActivityObserver (Updated) Hatası: " . $e->getMessage());
        }
    }

    public function deleted(CustomerActivity $activity): void
    {
        try {
            $event = Event::whereJsonContains('extras->linked_activity_id', $activity->id)->first();
            if ($event) {
                // Bağlı ziyareti ve etkinliği sil
                CustomerVisit::where('event_id', $event->id)->delete();
                $event->delete();
            }
        } catch (\Exception $e) {
            Log::error("CustomerActivityObserver (Deleted) Hatası: " . $e->getMessage());
        }
    }

    private function shouldSyncToCalendar(string $type): bool
    {
        $type = mb_strtolower($type, 'UTF-8');
        foreach (self::SYNCABLE_TYPES as $keyword) {
            if (str_contains($type, $keyword)) {
                return true;
            }
        }
        return false;
    }

    private function createEventFromActivity(CustomerActivity $activity): void
    {
        try {
            $dates = $this->calculateDates($activity);
            $eventType = $this->mapEventType($activity->type);

            // DİNAMİK DURUM KONTROLÜ: Tarih gelecekteyse Planlandı, geçmişteyse Gerçekleşti.
            $status = $dates['start']->isFuture() ? 'planlandi' : 'gerceklesti';

            // 1. TAKVİM KAYDINI (EVENT) OLUŞTUR
            $event = Event::create([
                'title'            => $this->generateTitle($activity),
                'description'      => $activity->description,
                'start_datetime'   => $dates['start'],
                'end_datetime'     => $dates['end'],
                'event_type'       => $eventType,
                'user_id'          => $activity->user_id,
                'customer_id'      => $activity->customer_id,
                'business_unit_id' => $activity->business_unit_id,
                'is_important'     => false,
                'visit_status'     => $status, // Dinamik Atama
                'location'         => $activity->customer ? $activity->customer->address : null,
                'extras'           => [
                    'linked_activity_id' => $activity->id,
                    'source'             => 'crm_module'
                ]
            ]);

            // 2. ZİYARETLER SEKMESİ İÇİN CUSTOMER_VISIT KAYDINI OLUŞTUR
            // Eğer bu bir 'visit' veya 'meeting' ise, ziyaretler sekmesinde de görünmeli
            if (in_array($eventType, ['visit', 'meeting'])) {
                CustomerVisit::create([
                    'event_id'      => $event->id,
                    'customer_id'   => $activity->customer_id,
                    'visit_purpose' => 'genel_gorusme', // Detay olmadığı için varsayılan amaç
                ]);
            }
        } catch (\Exception $e) {
            Log::error("CustomerActivityObserver (Create Helper) Hatası: " . $e->getMessage());
        }
    }

    private function updateEventFromActivity(Event $event, CustomerActivity $activity): void
    {
        $dates = $this->calculateDates($activity);
        $eventType = $this->mapEventType($activity->type);

        // 1. TAKVİM KAYDINI GÜNCELLE
        // NOT: update işleminde 'visit_status' bilerek güncellenmiyor. 
        // Çünkü kullanıcı takvim üzerinden "İptal Edildi" vs. seçmiş olabilir, CRM bu seçimi ezmemelidir.
        $event->update([
            'title'            => $this->generateTitle($activity),
            'description'      => $activity->description,
            'start_datetime'   => $dates['start'],
            'end_datetime'     => $dates['end'],
            'event_type'       => $eventType,
            'user_id'          => $activity->user_id,
            'customer_id'      => $activity->customer_id,
            'business_unit_id' => $activity->business_unit_id,
        ]);

        // 2. ZİYARETLER SEKMESİNİ GÜNCELLE VEYA OLUŞTUR
        if (in_array($eventType, ['visit', 'meeting'])) {
            // firstOrCreate: Eğer kayıt varsa dokunmaz (kullanıcı amacını elle değiştirmiş olabilir), yoksa oluşturur.
            CustomerVisit::firstOrCreate(
                ['event_id' => $event->id],
                [
                    'customer_id'   => $activity->customer_id,
                    'visit_purpose' => 'genel_gorusme'
                ]
            );
        } else {
            // Eğer tipi Toplantı/Ziyaret olmaktan çıktıysa, Ziyaretler sekmesinden kaldır
            CustomerVisit::where('event_id', $event->id)->delete();
        }
    }

    private function calculateDates(CustomerActivity $activity): array
    {
        $start = Carbon::parse($activity->activity_date);
        return [
            'start' => $start,
            'end'   => $start->copy()->addHour(), // Varsayılan 1 saat uzunluk
        ];
    }

    private function generateTitle(CustomerActivity $activity): string
    {
        $typeLabel = match ($activity->type) {
            'meeting' => 'Toplantı',
            'visit' => 'Müşteri Ziyareti',
            default => ucfirst($activity->type)
        };
        return $typeLabel . ' - ' . ($activity->customer ? $activity->customer->name : 'Müşteri');
    }

    private function mapEventType(string $activityType): string
    {
        $type = mb_strtolower($activityType, 'UTF-8');

        if (str_contains($type, 'toplantı') || str_contains($type, 'meeting')) {
            return 'meeting';
        }

        if (str_contains($type, 'ziyaret') || str_contains($type, 'visit')) {
            return 'visit';
        }

        return 'general';
    }
}
