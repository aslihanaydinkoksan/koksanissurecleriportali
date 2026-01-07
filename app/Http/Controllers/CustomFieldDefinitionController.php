<?php

namespace App\Http\Controllers;

use App\Models\CustomFieldDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BusinessUnit;

class CustomFieldDefinitionController extends Controller
{
    // List of Models in the System (For Admin selection)
    private $models = [
        'App\Models\Shipment' => 'Lojistik / Sevkiyat',
        'App\Models\Event' => 'Takvim / Etkinlik',
        'App\Models\MaintenancePlan' => 'Bakım Planı',
        'App\Models\ProductionPlan' => 'Üretim Planı',
        // In future 'App\Models\User' => 'Personel Kartı' etc. can be added
    ];

    public function index()
    {
        // Authorization Check (Middleware check is essential here if not present globally)
        if (!in_array(auth()->user()->role, ['admin', 'yönetici'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Get fields grouped by model
        $fields = CustomFieldDefinition::orderBy('model_scope')->orderBy('order')->get();

        return view('admin.custom_fields.index', compact('fields'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'yönetici'])) {
            abort(403);
        }

        $models = $this->models;
        // Fetch all business units to pass to the view
        $businessUnits = BusinessUnit::all();

        return view('admin.custom_fields.create', compact('models', 'businessUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'model_scope' => 'required',
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select,boolean,textarea,email',
            'options_text' => 'required_if:type,select|nullable|string', // Comma-separated options
            'business_unit_id' => 'nullable|exists:business_units,id', // Validate business unit
        ]);

        // Generate automatic key from Label (e.g., "Araç Plakası" -> "arac_plakasi")
        $key = Str::slug($request->label, '_');

        // Check if the key already exists in the same model scope
        $exists = CustomFieldDefinition::where('model_scope', $request->model_scope)
            ->where('key', $key)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bu isimde bir alan bu modülde zaten var.')->withInput();
        }

        // Process select options (Convert comma-separated string to array)
        $options = null;
        if ($request->type === 'select' && $request->options_text) {
            $options = array_map('trim', explode(',', $request->options_text));
        }

        CustomFieldDefinition::create([
            'model_scope' => $request->model_scope,
            'key' => $key,
            'label' => $request->label,
            'type' => $request->type,
            'options' => $options, // Sent as array, model casts to JSON
            'is_required' => $request->has('is_required'),
            'is_active' => true,
            'order' => $request->order ?? 0,
            'business_unit_id' => $request->business_unit_id, // Save business unit ID
        ]);

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Yeni alan başarıyla tanımlandı.');
    }

    public function edit($id)
    {
        $field = CustomFieldDefinition::findOrFail($id);
        $models = $this->models;
        $businessUnits = BusinessUnit::all(); // Fetch business units for edit view

        // Convert options array to comma-separated string (For display in Edit form)
        $optionsText = $field->options ? implode(', ', $field->options) : '';

        return view('admin.custom_fields.edit', compact('field', 'models', 'optionsText', 'businessUnits'));
    }

    public function update(Request $request, $id)
    {
        $field = CustomFieldDefinition::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'options_text' => 'required_if:type,select|nullable|string',
            'business_unit_id' => 'nullable|exists:business_units,id', // Validate business unit on update
        ]);

        // Note: Key (database column name) should not be changed to avoid data loss.
        // We only update the label, options, and other settings.

        $options = null;
        if ($field->type === 'select' && $request->options_text) {
            $options = array_map('trim', explode(',', $request->options_text));
        }

        $field->update([
            'label' => $request->label,
            'is_required' => $request->has('is_required'),
            'is_active' => $request->has('is_active'),
            'order' => $request->order,
            'options' => $options,
            'business_unit_id' => $request->business_unit_id, // Update business unit ID
        ]);

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Alan güncellendi.');
    }

    public function destroy($id)
    {
        // Instead of physical deletion, setting to passive is safer but
        // if admin wants to delete, let them delete.
        $field = CustomFieldDefinition::findOrFail($id);
        $field->delete();

        return redirect()->route('admin.custom-fields.index')
            ->with('success', 'Alan tanımı silindi. (Eski kayıtlardaki veriler JSON içinde durmaya devam eder ama formda görünmez.)');
    }
}