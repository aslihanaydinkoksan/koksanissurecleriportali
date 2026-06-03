<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $this->syncWithIaa($customer, 'created');
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        $this->syncWithIaa($customer, 'updated');
    }

    private function syncWithIaa(Customer $customer, string $action)
    {
        try {
            // iaa_projesi is running on port 8000 or production server
            $baseUrl = rtrim(config('services.iaa.url', env('IAA_URL', 'http://127.0.0.1:8000')), '/');
            $url = $baseUrl . '/api/customers/sync';
            
            Http::timeout(3)->post($url, [
                'is_syncing' => true,
                'action' => $action,
                'data' => [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'is_active' => $customer->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to sync customer to IAA: ' . $e->getMessage());
        }
    }
}
