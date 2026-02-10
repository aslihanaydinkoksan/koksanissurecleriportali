<?php

namespace App\Http\Controllers;

use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BusinessUnitController extends Controller
{
    /**
     * İşletme Birimlerini (Fabrikaları) Yönet
     */
    public function index()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $businessUnits = BusinessUnit::orderBy('name')->get();
        return view('business_units.index', compact('businessUnits'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:business_units,name',
            'code' => 'nullable|string|max:10|uppercase|unique:business_units,code',
        ]);

        BusinessUnit::create([
            'name' => $request->name,
            'code' => $request->code ?? Str::upper(Str::slug(Str::limit($request->name, 3, ''))),
            'slug' => Str::slug($request->name),
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Yeni işletme birimi/fabrika eklendi.');
    }

    public function destroy(BusinessUnit $businessUnit)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $businessUnit->delete();

        return redirect()->back()->with('success', 'İşletme birimi silindi.');
    }
}