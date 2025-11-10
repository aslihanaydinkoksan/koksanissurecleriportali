<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Sadece Admin'in erişebildiğinden emin ol (Constructor ile).
     */
    public function __construct()
    {
        // Bu controller'daki tüm metodlar için 'access-admin-features' Gate'ini (veya kendi admin middleware'inizi) uygula
        $this->middleware('can:access-admin-features');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 'withCount' ile her departmanda kaç kullanıcı olduğunu da say
        $departments = Department::withCount('users')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|alpha_dash|unique:departments,slug',
        ]);

        Department::create($validatedData);

        return redirect()->route('departments.index')
            ->with('success', 'Yeni departman başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     * (Bu metodu kullanmıyoruz - 'except' ile rotalardan çıkardık)
     */
    public function show(Department $department)
    {
        return redirect()->route('departments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('departments')->ignore($department->id),
            ],
        ]);

        $department->update($validatedData);

        return redirect()->route('departments.index')
            ->with('success', 'Departman başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // Ana departmanların silinmesini engelle (Ekstra koruma)
        if (in_array($department->slug, ['lojistik', 'uretim', 'hizmet'])) {
            return redirect()->route('departments.index')
                ->with('error', 'Ana sistem departmanları (lojistik, üretim, hizmet) silinemez.');
        }

        try {
            $department->delete();
            return redirect()->route('departments.index')
                ->with('success', 'Departman başarıyla silindi.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Veritabanı (SQL) hatası yakala
            Log::error("Departman silme hatası: " . $e->getMessage());

            if ($e->errorInfo[1] == 1451) {
                // Hata kodu 1451 (Foreign Key Constraint Violation) ise:
                return redirect()->route('departments.index')
                    ->with('error', 'Bu departman silinemedi. Lütfen önce bu departmana bağlı olan tüm kullanıcıların departmanını değiştirin.');
            }

            return redirect()->route('departments.index')
                ->with('error', 'Departman silinirken bir veritabanı hatası oluştu: ' . $e->getMessage());
        }
    }
}
