<?php

namespace App\Http\Controllers;

use App\Models\Birim;
use Illuminate\Http\Request;

class BirimController extends Controller
{
    // Sadece adminlerin bu controller'a erişebilmesini sağlar
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    
    /**
     * Tüm birimleri listeler ve ekleme formu gösterir.
     */
    public function index()
    {
        $birimler = Birim::orderBy('ad')->get();
        return view('birimler.index', compact('birimler'));
    }

    /**
     * Yeni bir birim kaydeder.
     */
    public function store(Request $request)
    {
        // 1. Veriyi doğrula ve sadece 'ad' alanını içeren bir dizi olarak al
        $validatedData = $request->validate([
            'ad' => 'required|string|max:255|unique:birims,ad',
        ], [
            'ad.required' => 'Birim adı zorunludur.',
            'ad.unique' => 'Bu birim zaten mevcut.',
        ]);

        // 2. DÜZELTME: $request->all() yerine, sadece doğrulanan veriyi kullan
        Birim::create($validatedData);

        return redirect()->route('birimler.index')->with('success', 'Yeni birim başarıyla eklendi.');
    }

    /**
     * Belirtilen birimi siler.
     */
    public function destroy(Birim $birim)
    {
        $birim->delete();
        return redirect()->route('birimler.index')->with('success', 'Birim başarıyla silindi.');
    }
}