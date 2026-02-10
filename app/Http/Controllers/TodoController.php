<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Yeni Görev Ekle
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);

        // Aktif Business Unit'i bul
        $unitId = session('active_unit_id');

        // Eğer session yoksa ve kullanıcı bir birime bağlıysa ilkini al
        if (!$unitId) {
            $unitId = Auth::user()->businessUnits->first()?->id;
        }

        $todo = Todo::create([
            'user_id' => Auth::id(),
            'business_unit_id' => $unitId, // Otomatik atanır
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date ? Carbon::parse($request->due_date) : null,
            'priority' => $request->priority,
        ]);

        return response()->json(['success' => true, 'data' => $todo, 'message' => 'Görev eklendi.']);
    }

    /**
     * Durum Güncelle (Tamamlandı/Devam Ediyor)
     */
    public function toggle(Todo $todo)
    {
        // Güvenlik: Sadece kendi görevini güncelleyebilir
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $todo->update(['is_completed' => !$todo->is_completed]);

        return response()->json(['success' => true, 'new_status' => $todo->is_completed]);
    }

    /**
     * Sil
     */
    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $todo->delete();
        return response()->json(['success' => true]);
    }
}
