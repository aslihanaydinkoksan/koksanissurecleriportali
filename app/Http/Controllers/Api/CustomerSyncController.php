<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\BusinessUnit;
use App\Models\CustomerContact;
use App\Models\User;

class CustomerSyncController extends Controller
{
    public function sync(Request $request)
    {
        // Avoid looping
        if (!$request->input('is_syncing'))
        {
            return response()->json(['status' => 'ignored']);
        }

        $type = $request->input('type', 'customer'); // 'customer', 'contact', 'complaint', 'return', 'user'
        $data = $request->input('data');

        if (!$data)
        {
            return response()->json(['status' => 'error', 'message' => 'No data provided'], 400);
        }

        try
        {
            if ($type === 'customer')
            {
                return $this->syncCustomer($data);
            }
            elseif ($type === 'contact')
            {
                return $this->syncContact($data);
            }
            elseif ($type === 'complaint')
            {
                return $this->syncComplaint($data);
            }
            elseif ($type === 'return')
            {
                return $this->syncReturn($data);
            }
            elseif ($type === 'user')
            {
                return $this->syncUser($data);
            }
        }
        catch (\Exception $e)
        {
            Log::error("Customer sync ($type) failed: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'ignored']);
    }

    private function syncReturn($data)
    {
        $targetBU = $data['business_unit_id'] ?? null;
        $customer = null;
        if (!empty($data['customer_email']))
        {
            $customer = Customer::where('email', $data['customer_email']);
            if ($targetBU)
                $customer->where('business_unit_id', $targetBU);
            $customer = $customer->first();
        }
        if (!$customer && !empty($data['customer_name']))
        {
            $customer = Customer::where('name', $data['customer_name']);
            if ($targetBU)
                $customer->where('business_unit_id', $targetBU);
            $customer = $customer->first();
        }

        if (!$customer)
        {
            // Fallback to any BU if not found in targeted BU (to maintain compatibility)
            $customer = Customer::where('email', $data['customer_email'])->first()
                ?? Customer::where('name', $data['customer_name'])->first();
        }

        if (!$customer)
        {
            throw new \Exception("Customer not found for return sync: " . ($data['customer_name'] ?? 'Unknown'));
        }

        // Must link to a complaint
        $complaint = \App\Models\Complaint::withoutGlobalScopes()
            ->where('remote_id', $data['remote_complaint_id'])
            ->where('remote_system', 'iaa')
            ->first();

        if (!$complaint)
        {
            throw new \Exception("Complaint not found for return sync (Remote ID: " . $data['remote_complaint_id'] . ")");
        }

        $return = \App\Models\CustomerReturn::withoutGlobalScopes()
            ->where('remote_id', $data['remote_id'])
            ->where('remote_system', 'iaa')
            ->first();

        if (!$return)
        {
            $return = new \App\Models\CustomerReturn();
            $return->remote_id = $data['remote_id'];
            $return->remote_system = 'iaa';
            $return->customer_id = $customer->id;
            $return->complaint_id = $complaint->id;
            $return->user_id = 1; // Default
            $return->business_unit_id = $data['business_unit_id'] ?? $customer->business_unit_id;
        }

        \App\Models\CustomerReturn::withoutEvents(function () use ($return, $data, $customer) {
            $return->customer_id = $customer->id;
            $return->business_unit_id = $data['business_unit_id'] ?? $customer->business_unit_id;
            $return->product_name = $data['product_name'] ?? $return->product_name;
            $return->quantity = $data['quantity'] ?? ($return->quantity ?? 0);
            $return->unit = $data['unit'] ?? $return->unit;
            $return->shipped_quantity = $data['shipped_quantity'] ?? ($return->shipped_quantity ?? 0);
            $return->shipped_unit = $data['shipped_unit'] ?? $return->shipped_unit;
            $return->reason = $data['reason'] ?? $return->reason;
            $return->return_date = $data['return_date'] ?? $return->return_date;
            $return->remote_url = $data['remote_url'] ?? $return->remote_url;

            if ($return->remote_system === 'iaa')
            {
                $return->status = 'completed';
            }

            $return->save();
        });

        return response()->json(['status' => 'success', 'message' => 'Return synced successfully']);
    }

    private function syncUser($data)
    {
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        foreach ($businessUnits as $unit)
        {
            $user = User::where('email', $data['email'])->first();
            if (!$user)
            {
                $user = new User();
                $user->email = $data['email'];
                $user->password = bcrypt(Str::random(16));
            }

            User::withoutEvents(function () use ($user, $data) {
                $user->name = $data['name'] ?? $user->name;
                $user->save();
            });
        }

        return response()->json(['status' => 'success', 'message' => 'User synced successfully across all units']);
    }

    private function syncCustomer($data)
    {
        Log::info("Syncing customer data received: " . json_encode($data));
        $businessUnits = BusinessUnit::where('is_active', true)->get();
        Log::info("Active business units count: " . $businessUnits->count());

        foreach ($businessUnits as $unit)
        {
            $customer = null;
            Log::info("Syncing for business unit: " . $unit->name);

            // 1. Match by remote_id
            if (!empty($data['remote_id']))
            {
                $customer = Customer::where('remote_id', $data['remote_id'])
                    ->where('remote_system', 'iaa')
                    ->where('business_unit_id', $unit->id)
                    ->first();
                if ($customer)
                    Log::info("Found existing customer by remote_id: " . $customer->id);
            }

            // 2. Fallback to email/name if remote_id not found (legacy matching)
            if (!$customer)
            {
                if (!empty($data['email']))
                {
                    $customer = Customer::where('email', $data['email'])
                        ->where('business_unit_id', $unit->id)
                        ->first();
                }

                if (!$customer && !empty($data['name']))
                {
                    $customer = Customer::where('name', $data['name'])
                        ->where('business_unit_id', $unit->id)
                        ->first();
                }
            }

            if (!$customer)
            {
                $customer = new Customer();
                $customer->business_unit_id = $unit->id;
                $customer->remote_id = $data['remote_id'] ?? null;
                $customer->remote_system = 'iaa';
                $customer->start_date = now();
                $customer->end_date = now()->addYear();
                $customer->name = $data['name'];
            }

            Customer::withoutEvents(function () use ($customer, $data) {
                $customer->name = $data['name'] ?? $customer->name;
                $customer->email = $data['email'] ?? $customer->email;
                $customer->phone = $data['phone'] ?? $customer->phone;
                $customer->address = $data['address'] ?? $customer->address;
                $customer->remote_id = $data['remote_id'] ?? $customer->remote_id;
                $customer->remote_system = 'iaa';
                if (isset($data['is_active']))
                {
                    $customer->is_active = $data['is_active'];
                }
                $customer->save();
            });
        }

        return response()->json(['status' => 'success', 'message' => 'Customer synced successfully across all units']);
    }

    private function syncContact($data)
    {
        Log::info("Syncing contact across all units: " . json_encode($data));
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        foreach ($businessUnits as $unit)
        {
            $customer = null;

            // Match customer in THIS unit (by remote_id if available)
            if (!empty($data['customer_remote_id']))
            {
                $customer = Customer::where('remote_id', $data['customer_remote_id'])
                    ->where('business_unit_id', $unit->id)
                    ->where('remote_system', 'iaa')
                    ->first();
            }

            if (!$customer)
            {
                $customer = Customer::where('business_unit_id', $unit->id)
                    ->where(function ($q) use ($data) {
                    if (!empty($data['customer_email']))
                    {
                        $q->where('email', $data['customer_email']);
                    }
                    if (!empty($data['customer_name']))
                    {
                        $q->orWhere('name', $data['customer_name']);
                    }
                })->first();
            }

            if (!$customer)
            {
                continue;
            }

            $contact = null;

            // 1. Match by remote_id
            if (!empty($data['remote_id']))
            {
                $contact = CustomerContact::where('remote_id', $data['remote_id'])
                    ->where('remote_system', 'iaa')
                    ->where('customer_id', $customer->id)
                    ->first();
            }

            // 2. Fallback
            if (!$contact)
            {
                $contact = CustomerContact::where('customer_id', $customer->id)
                    ->where('email', $data['email'])
                    ->first();

                if (!$contact)
                {
                    $contact = CustomerContact::where('customer_id', $customer->id)
                        ->where('name', $data['name'])
                        ->first();
                }
            }

            if (!$contact)
            {
                $contact = new CustomerContact();
                $contact->customer_id = $customer->id;
                $contact->business_unit_id = $unit->id;
                $contact->remote_id = $data['remote_id'] ?? null;
                $contact->remote_system = 'iaa';
                $contact->email = $data['email'];
            }

            CustomerContact::withoutEvents(function () use ($contact, $data) {
                $contact->name = $data['name'] ?? $contact->name;
                $contact->email = $data['email'] ?? $contact->email;
                $contact->phone = $data['phone'] ?? $contact->phone;
                $contact->title = $data['title'] ?? $contact->title;
                $contact->remote_id = $data['remote_id'] ?? $contact->remote_id;
                $contact->remote_system = 'iaa';
                $contact->save();
            });
        }

        return response()->json(['status' => 'success', 'message' => 'Contact synced successfully across all units']);
    }

    private function syncComplaint($data)
    {
        $targetBU = $data['business_unit_id'] ?? null;
        $remoteId = $data['remote_id'] ?? null;

        $customer = null;
        if (!empty($data['customer_email']))
        {
            $customer = Customer::withoutGlobalScopes()->where('email', $data['customer_email']);
            if ($targetBU)
                $customer->where('business_unit_id', $targetBU);
            $customer = $customer->first();
        }
        if (!$customer && !empty($data['customer_name']))
        {
            $customer = Customer::withoutGlobalScopes()->where('name', $data['customer_name']);
            if ($targetBU)
                $customer->where('business_unit_id', $targetBU);
            $customer = $customer->first();
        }

        if (!$customer)
        {
            // Fallback to any BU
            $customer = Customer::withoutGlobalScopes()->where('email', $data['customer_email'])->first()
               ?? Customer::withoutGlobalScopes()->where('name', $data['customer_name'])->first();
        }

        if (!$customer)
        {
            throw new \Exception("Customer not found for complaint sync: " . ($data['customer_name'] ?? 'Unknown'));
        }

        $complaint = null;
        if ($remoteId)
        {
            // 1. remote_id ile mevcut kaydı bul
            $complaint = \App\Models\Complaint::withoutGlobalScopes()
                ->where('remote_id', $remoteId)
                ->where('remote_system', 'iaa')
                ->first();

            // 2. MÜKERRER TEMİZLİĞİ: Daha agresif temizlik
            if ($customer)
            {
                // Sadece başlık değil, aynı remote_id'ye sahip ama farklı internal ID'li olanları da temizleyelim (garanti olsun)
                $duplicates = \App\Models\Complaint::withoutGlobalScopes()
                    ->where('customer_id', $customer->id)
                    ->where(function ($q) use ($remoteId, $data) {
                    // Aynı remote_id'ye sahip ama sistemde başka kaydı olanlar
                    $q->where('remote_id', $remoteId)
                      ->where('remote_system', 'iaa');

                    // VEYA aynı başlığa sahip (başlıkta boşluk temizliği yaparak) ama remote_id'si olmayanlar
                    if (!empty($data['title']))
                    {
                        $q->orWhere(function ($sq) use ($data) {
                                    $trimmedTitle = trim($data['title']);
                                    $sq->whereRaw('TRIM(title) = ?', [$trimmedTitle])
                                       ->where(function ($ssq) {
                                $ssq->whereNull('remote_id')
                                    ->orWhere('remote_id', '');
                            }
                            );
                        }
                         );
                    }
                })
                    ->when($complaint, function ($q) use ($complaint) {
                    return $q->where('id', '!=', $complaint->id);
                })
                    ->get();

                foreach ($duplicates as $dup)
                {
                    \Log::info("Deleting duplicate complaint ID: {$dup->id} for Remote ID: {$remoteId} (Title: {$dup->title})");
                    $dup->forceDelete();
                }
            }
        }

        if (!$complaint)
        {
            $complaint = new \App\Models\Complaint();
            $complaint->remote_id = $remoteId;
            $complaint->remote_system = 'iaa';
        }

        \App\Models\Complaint::withoutEvents(function () use ($complaint, $data, $customer, $targetBU) {
            $complaint->customer_id = $customer->id;
            // Use provided business_unit_id, fall back to customer's, or keep existing
            $complaint->business_unit_id = $targetBU ?? ($customer->business_unit_id ?? $complaint->business_unit_id);
            $complaint->user_id = $complaint->user_id ?? 1; // Default to admin if new
            $complaint->title = $data['title'] ?? $complaint->title;
            $complaint->description = $data['description'] ?? $complaint->description;
            $complaint->status = $data['status'] ?? $complaint->status;
            $complaint->remote_url = $data['remote_url'] ?? $complaint->remote_url;
            $complaint->remote_creator_name = $data['remote_creator_name'] ?? ($complaint->remote_creator_name ?? 'Sistem');
            $complaint->save();

            // DOSYA SENKRONİZASYONU
            if (!empty($data['attachments']) && is_array($data['attachments'])) {
                foreach ($data['attachments'] as $fileData) {
                    try {
                        $fileName = $fileData['name'] ?? basename($fileData['url']);
                        
                        // Aynı isimde dosya zaten var mı kontrol et (mükerrerliği önlemek için)
                        $exists = $complaint->hasMedia('complaint_attachments') && 
                                  $complaint->getMedia('complaint_attachments')->contains('file_name', $fileName);

                        if (!$exists && !empty($fileData['url'])) {
                            $complaint->addMediaFromUrl($fileData['url'])
                                ->usingFileName($fileName)
                                ->toMediaCollection('complaint_attachments');
                            
                            \Log::info("File synced locally to Takvim: $fileName for Complaint #{$complaint->id}");
                        }
                    } catch (\Exception $fe) {
                        \Log::error("Failed to sync individual file for complaint #{$complaint->id}: " . $fe->getMessage());
                    }
                }
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Complaint synced successfully', 'id' => $complaint->id]);
    }

    public function getVisitData(Request $request)
    {
        try
        {
            $customerName = trim($request->query('customer_name'));
            $buId = $request->query('business_unit_id');
            $remoteId = $request->query('remote_id'); // This is usually project_id from IAA

            \Log::info("Takvim getVisitData search: Name=[$customerName], BU=[$buId], RemoteID/ProjectID=[$remoteId]");

            // 1. Müşteriyi bul (Global scope olmadan)
            $customer = Customer::withoutGlobalScopes()->where('name', $customerName);
            if ($buId)
            {
                $customer = $customer->where('business_unit_id', $buId);
            }
            $customer = $customer->first();

            // 2. Fallback: Eğer ünitede bulunamazsa herhangi bir ünitedeki aynı isimli müşteriyi bul
            if (!$customer)
            {
                $customer = Customer::withoutGlobalScopes()->where('name', $customerName)->first();
            }

            if (!$customer)
            {
                return response()->json(['error' => 'Customer not found: ' . $customerName], 404);
            }

            // 3. Mevcut ziyaret varsa çek (HAYATİ: Sütun kontrolü yapalım ki 500 vermesin)
            $existingVisit = null;
            if ($remoteId && \Illuminate\Support\Facades\Schema::hasColumn('customer_visits', 'remote_id'))
            {
                $query = \App\Models\CustomerVisit::withoutGlobalScopes()
                    ->where('remote_id', $remoteId)
                    ->with(['product', 'complaint']);

                if (\Illuminate\Support\Facades\Schema::hasColumn('customer_visits', 'remote_system'))
                {
                    $query->where('remote_system', 'iaa');
                }

                $existingVisit = $query->first();
            }

            // 4. Ürünleri çek (Müşterinin tüm birimlerdeki ürünlerini alabiliriz daha geniş kapsam için)
            $customerIds = Customer::withoutGlobalScopes()->where('name', trim($customerName))->pluck('id');
            $products = \App\Models\CustomerProduct::withoutGlobalScopes()
                ->whereIn('customer_id', $customerIds)
                ->get(['id', 'name']);

            return response()->json([
                'customer_id' => $customer->id,
                'products' => $products,
                'contacts' => $customer->contacts()->get(['id', 'name']),
                'complaints' => $customer->complaints()->get(['id', 'title', 'remote_id']),
                'users' => \App\Models\User::all(['id', 'name', 'email']), // simpler all() for users
                'visit_reasons' => [
                    'Şikayet İnceleme',
                    'Ürün Denemesi',
                    'Teknik Destek',
                    'Periyodik Ziyaret',
                    'Rutin Ziyaret',
                    'Diğer'
                ],
                'existing_visit' => $existingVisit
            ]);
        }
        catch (\Exception $e)
        {
            \Log::error("Takvim getVisitData CRITICAL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Server Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function storeVisit(Request $request)
    {
        \Log::info('Takvim storeVisit Incoming Request: ' . json_encode($request->all()));

        $customerName = trim($request->input('customer_name'));
        $customerId = $request->input('customer_id');
        $businessUnitId = $request->input('business_unit_id');

        // IF customer_id is NULL but Name is provided, try to find it
        if (empty($customerId) && !empty($customerName))
        {
            // First try: match by name AND business_unit_id (most precise)
            $customer = null;
            if (!empty($businessUnitId))
            {
                $customer = Customer::withoutGlobalScopes()
                    ->where('name', $customerName)
                    ->where('business_unit_id', $businessUnitId)
                    ->first();
            }
            // Fallback: match by name only
            if (!$customer)
            {
                $customer = Customer::withoutGlobalScopes()->where('name', $customerName)->first();
            }

            if ($customer)
            {
                $request->merge(['customer_id' => $customer->id]);
                $customerId = $customer->id;
                \Log::info('Takvim storeVisit: Auto-resolved customer_id to ' . $customerId . ' (BU=' . $customer->business_unit_id . ') from name: ' . $customerName);
            }
            else
            {
                \Log::error('Takvim storeVisit: Could not find customer by name: ' . $customerName);
                return response()->json(['status' => 'error', 'message' => 'Customer not found: ' . $customerName], 404);
            }
        }

        // Validate
        $data = $request->validate([
            'customer_id' => 'required|integer',
            'visit_date' => 'required',
            'visit_reason' => 'required',
            'visit_notes' => 'nullable',
            'contact_persons' => 'nullable|array',
            'customer_product_id' => 'nullable',
            'barcode' => 'nullable',
            'lot_no' => 'nullable',
            'findings' => 'nullable',
            'result' => 'nullable',
            'complaint_id' => 'nullable',
            'remote_id' => 'nullable',
            'remote_system' => 'nullable',
            'remote_url' => 'nullable',
            'user_id' => 'nullable|integer',
            'business_unit_id' => 'nullable|integer',
            'visitor_id' => 'nullable|integer',
            'visitor_name' => 'nullable|string',
            'estimated_return_date' => 'nullable|date',
            'visit_files' => 'nullable|array',
            'visit_files.*.name' => 'nullable|string',
            'visit_files.*.url' => 'nullable|string',
        ]);

        $visit = null;
        if (!empty($data['remote_id']) && !empty($data['remote_system']))
        {
            $visit = \App\Models\CustomerVisit::withoutGlobalScopes()
                ->where('remote_id', $data['remote_id'])
                ->where('remote_system', $data['remote_system'])
                ->first();
        }

        if ($visit && $visit->is_locked)
        {
            return response()->json(['status' => 'error', 'message' => 'Visit is locked and cannot be updated'], 403);
        }

        if (!$visit)
        {
            $visit = new \App\Models\CustomerVisit();
        }

        if (empty($data['visitor_id']) && !empty($data['visitor_name']))
        {
            $visitor = User::withoutGlobalScopes()->where('name', $data['visitor_name'])->first();
            if ($visitor)
            {
                $data['visitor_id'] = $visitor->id;
            }
        }

        $visit->fill($data);
        $visit->user_id = $data['user_id'] ?? 1;

        // Ensure business_unit_id is set
        if (empty($data['business_unit_id']))
        {
            $customer = Customer::withoutGlobalScopes()->find($data['customer_id']);
            $visit->business_unit_id = $customer ? $customer->business_unit_id : null;
        }
        else
        {
            $visit->business_unit_id = $data['business_unit_id'];
        }

        $visit->save();

        \Log::info('Takvim storeVisit: SAVED visit ID=' . $visit->id . ' for customer_id=' . $data['customer_id'] . ' remote_id=' . ($data['remote_id'] ?? 'none'));

        return response()->json([
            'status' => 'success',
            'visit_id' => $visit->id,
            'visit' => $visit->load(['product', 'complaint', 'user'])
        ]);
    }

    public function toggleVisitLock(Request $request)
    {
        $remoteId = $request->input('remote_id');
        $lock = $request->input('lock', true);

        $visit = \App\Models\CustomerVisit::where('remote_id', $remoteId)
            ->where('remote_system', 'iaa')
            ->first();

        if (!$visit)
        {
            return response()->json(['status' => 'error', 'message' => 'Visit not found'], 404);
        }

        $visit->update(['is_locked' => $lock]);

        return response()->json([
            'status' => 'success',
            'is_locked' => $visit->is_locked
        ]);
    }

    public function getVisitStats(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = \App\Models\CustomerVisit::query();

        if ($startDate)
        {
            $query->whereDate('visit_date', '>=', $startDate);
        }
        if ($endDate)
        {
            $query->whereDate('visit_date', '<=', $endDate);
        }

        $totalVisits = (clone $query)->count();

        $reasonDistribution = (clone $query)
            ->select('visit_reason', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('visit_reason')
            ->orderByDesc('total')
            ->pluck('total', 'visit_reason');

        $visitsByComplaint = (clone $query)
            ->whereNotNull('remote_id')
            ->where('remote_system', 'iaa')
            ->select('remote_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('remote_id')
            ->pluck('total', 'remote_id');

        $recentVisits = (clone $query)
            ->whereNotNull('remote_id')
            ->where('remote_system', 'iaa')
            ->orderByDesc('visit_date')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        return response()->json([
            'total_visits' => $totalVisits,
            'reason_distribution' => $reasonDistribution,
            'visits_by_complaint' => $visitsByComplaint,
            'recent_visits' => $recentVisits
        ]);
    }

    public function getVisitsList(Request $request)
    {
        $remoteIds = $request->input('remote_ids');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $perPage = $request->query('per_page', 15);

        $query = \App\Models\CustomerVisit::query()
            ->with(['product', 'complaint', 'user', 'customer'])
            ->where('remote_system', 'iaa')
            ->orderByDesc('visit_date')
            ->orderByDesc('id');

        if (!empty($remoteIds) && is_array($remoteIds))
        {
            $query->whereIn('remote_id', $remoteIds);
        }
        elseif ($remoteIds === '*')
        {
        // Show all
        }
        else
        {
            return response()->json([
                'data' => [],
                'total' => 0
            ]);
        }

        if ($startDate)
        {
            $query->whereDate('visit_date', '>=', $startDate);
        }
        if ($endDate)
        {
            $query->whereDate('visit_date', '<=', $endDate);
        }

        $visits = $query->paginate($perPage);

        return response()->json($visits);
    }

    public function getBusinessUnits()
    {
        return response()->json(BusinessUnit::where('is_active', true)->get(['id', 'name']));
    }

    public function deleteComplaint(Request $request)
    {
        $remoteId = $request->input('remote_id');
        $force = $request->input('force', false);

        if ($remoteId)
        {
            $complaint = \App\Models\Complaint::withTrashed()
                ->where('remote_id', $remoteId)
                ->where('remote_system', 'iaa')
                ->first();

            if ($complaint)
            {
                if ($force)
                {
                    $complaint->forceDelete();
                }
                else
                {
                    $complaint->delete();
                }
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function restoreComplaint(Request $request)
    {
        $remoteId = $request->input('remote_id');
        if ($remoteId)
        {
            \App\Models\Complaint::withTrashed()
                ->where('remote_id', $remoteId)
                ->where('remote_system', 'iaa')
                ->restore();
        }
        return response()->json(['status' => 'success']);
    }
}
