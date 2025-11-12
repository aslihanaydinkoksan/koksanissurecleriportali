<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Birim
 *
 * @property int $id
 * @property string $ad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Birim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim query()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereAd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Birim withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Birim withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperBirim
 */
	class Birim extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $travel_id
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
 * @property-read \App\Models\Travel $travel
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperBooking
 */
	class Booking extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
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
	class Complaint extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Complaint> $complaints
 * @property-read int|null $complaints_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerMachine> $machines
 * @property-read int|null $machines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestResult> $testResults
 * @property-read int|null $test_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerVisit> $visits
 * @property-read int|null $visits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperCustomer
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerActivityLog
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $model_type
 * @property int $model_id
 * @property string $action
 * @property array|null $changes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerActivityLog whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerActivityLog
 */
	class CustomerActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerMachine
 *
 * @property int $id
 * @property int $customer_id
 * @property string $model
 * @property string|null $serial_number
 * @property string|null $installation_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereInstallationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerMachine whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerMachine
 */
	class CustomerMachine extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerVisit
 *
 * @property int $id
 * @property int $event_id
 * @property int $customer_id
 * @property int|null $travel_id
 * @property int|null $customer_machine_id
 * @property string $visit_purpose
 * @property string|null $after_sales_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Travel|null $travel
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereAfterSalesNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerVisit whereVisitPurpose($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCustomerVisit
 */
	class CustomerVisit extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Department
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Department withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperDepartment
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Event
 *
 * @property int $id
 * @property int $user_id
 * @property bool $is_important
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $start_datetime
 * @property \Illuminate\Support\Carbon $end_datetime
 * @property string|null $location
 * @property string $event_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\CustomerVisit|null $customerVisit
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEndDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereStartDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Event withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperEvent
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductionPlan
 *
 * @property int $id
 * @property int $user_id
 * @property bool $is_important
 * @property string $plan_title
 * @property \Illuminate\Support\Carbon $week_start_date
 * @property array|null $plan_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan wherePlanDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan wherePlanTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan whereWeekStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductionPlan withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperProductionPlan
 */
	class ProductionPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ServiceSchedule
 *
 * @property int $id
 * @property string $name
 * @property string $departure_time
 * @property int $cutoff_minutes
 * @property int|null $default_vehicle_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Vehicle|null $defaultVehicle
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereCutoffMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDefaultVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceSchedule withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperServiceSchedule
 */
	class ServiceSchedule extends \Eloquent {}
}

namespace App\Models{
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
	class Shipment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TestResult
 *
 * @property int $id
 * @property int $customer_id
 * @property int $user_id
 * @property int|null $customer_machine_id
 * @property string $test_name
 * @property string $test_date
 * @property string|null $summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Customer $customer
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereCustomerMachineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereTestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereTestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TestResult whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTestResult
 */
	class TestResult extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Travel
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property int $is_important
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerVisit> $customerVisits
 * @property-read int|null $customer_visits_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperTravel
 */
	class Travel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $department_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Department|null $department
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Travel> $travels
 * @property-read int|null $travels_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperUser
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Vehicle
 *
 * @property int $id
 * @property string $plate_number
 * @property string $type
 * @property string|null $brand_model
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleAssignment> $assignments
 * @property-read int|null $assignments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereBrandModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle wherePlateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperVehicle
 */
	class Vehicle extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VehicleAssignment
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int $user_id
 * @property bool $is_important
 * @property string $task_description
 * @property string|null $destination
 * @property string|null $requester_name
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereIsImportant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereRequesterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereTaskDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment whereVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|VehicleAssignment withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperVehicleAssignment
 */
	class VehicleAssignment extends \Eloquent {}
}

