<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        // Model sınıfını tam yoluna çevir (Güvenlik için whitelist yapılabilir)
        // Örn: 'vehicle_assignment' gelirse 'App\Models\VehicleAssignment' yap
        $modelClass = $request->input('model_type');

        if (!class_exists($modelClass)) {
            return back()->with('error', 'Geçersiz model türü.');
        }

        $model = $modelClass::find($request->input('model_id'));
        if (!$model) {
            return back()->with('error', 'Kayıt bulunamadı.');
        }

        // Dosyayı Yükle
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();

            // Klasör yapısı: uploads/modeller/id (Örn: uploads/vehicle_assignments/50)
            $folder = 'uploads/' . class_basename($model) . '/' . $model->id;
            $path = $file->store($folder, 'public');

            // Veritabanına Kaydet
            $model->files()->create([
                'path' => $path,
                'original_name' => $originalName,
                'mime_type' => $file->getClientMimeType(),
                'uploaded_by' => Auth::id(),
            ]);

            return back()->with('success', 'Dosya başarıyla yüklendi.');
        }

        return back()->with('error', 'Dosya seçilmedi.');
    }

    public function destroy(File $file)
    {
        // Yetki kontrolü (Sadece yükleyen veya admin silebilir)
        if (Auth::id() !== $file->uploaded_by && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Dosyayı diskten sil
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Veritabanından sil
        $file->delete();

        return back()->with('success', 'Dosya silindi.');
    }

    public function download(File $file)
    {
        // İndirme işlemi
        return Storage::disk('public')->download($file->path, $file->original_name);
    }
}