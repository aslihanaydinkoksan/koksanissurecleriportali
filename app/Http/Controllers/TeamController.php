<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamController extends Controller
{
    use HasFactory, SoftDeletes;
    /**
     * Takımları listeler.
     */
    public function index(): View
    {
        $teams = Team::with(['users', 'creator'])
            ->withCount('users') // Üye sayısını otomatik hesaplar
            ->latest()
            ->paginate(15);

        return view('teams.index', compact('teams'));
    }

    /**
     * Yeni takım oluşturma formu.
     */
    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('teams.create', compact('users'));
    }

    /**
     * Yeni takımı kaydeder.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'description' => 'nullable|string|max:1000',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id',
        ], [
            'name.required' => 'Takım adı zorunludur.',
            'name.unique' => 'Bu isimde bir takım zaten mevcut.',
            'members.required' => 'Takıma en az bir üye eklemelisiniz.',
            'members.min' => 'Takıma en az bir üye eklemelisiniz.',
        ]);

        // Takımı oluştur
        $team = Team::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'created_by_user_id' => Auth::id(),
            'is_active' => true,
        ]);

        // Üyeleri ekle
        $team->users()->attach($validatedData['members']);

        // AJAX isteği ise JSON döndür
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Takım başarıyla oluşturuldu.',
                'team' => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                    'members_count' => count($validatedData['members'])
                ]
            ], 201);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Takım başarıyla oluşturuldu.');
    }

    /**
     * Takım detaylarını gösterir.
     */
    public function show(Team $team): View
    {
        $team->load(['users', 'creator', 'assignments']);

        return view('teams.show', compact('team'));
    }

    /**
     * Takım düzenleme formu.
     */
    public function edit(Team $team): View|RedirectResponse
    {
        try {
            $team->load('users');
            $selectedUserIds = $team->users->pluck('id')->toArray();
            $users = User::orderBy('name')->get();

            return view('teams.edit', compact('team', 'users', 'selectedUserIds'));

        } catch (ModelNotFoundException $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Takım bulunamadı.');
        }
    }

    /**
     * Takımı günceller.
     */
    public function update(Request $request, Team $team): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:1000',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id',
            'is_active' => 'sometimes|boolean',
        ], [
            'name.required' => 'Takım adı zorunludur.',
            'name.unique' => 'Bu isimde bir takım zaten mevcut.',
            'members.required' => 'Takıma en az bir üye eklemelisiniz.',
            'members.min' => 'Takıma en az bir üye eklemelisiniz.',
        ]);

        // Takımı güncelle
        $team->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'is_active' => $validatedData['is_active'] ?? $team->is_active,
        ]);

        // Üyeleri güncelle
        $team->users()->sync($validatedData['members']);

        return redirect()->route('teams.index')
            ->with('success', 'Takım başarıyla güncellendi.');
    }

    /**
     * Takımı siler.
     */
    public function destroy(Team $team): RedirectResponse
    {
        try {
            // Takıma atanmış aktif görev var mı kontrol et
            $activeAssignments = $team->assignments()
                ->whereIn('status', ['pending', 'in_progress'])
                ->count();

            if ($activeAssignments > 0) {
                return redirect()->route('teams.index')
                    ->with('error', "Bu takım silinemedi. Takıma atanmış {$activeAssignments} aktif görev bulunuyor.");
            }

            $team->delete();

            return redirect()->route('teams.index')
                ->with('success', 'Takım başarıyla silindi.');

        } catch (\Exception $e) {
            return redirect()->route('teams.index')
                ->with('error', 'Bu takım silinemedi. Beklenmeyen bir hata oluştu.');
        }
    }

    /**
     * Takımı aktif/pasif yapar.
     */
    public function toggleActive(Team $team): RedirectResponse|JsonResponse
    {
        $team->is_active = !$team->is_active;
        $team->save();

        $message = $team->is_active
            ? 'Takım aktif edildi.'
            : 'Takım pasif edildi.';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $team->is_active
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Takıma üye ekler (AJAX).
     */
    public function addMember(Request $request, Team $team): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($team->users()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kullanıcı zaten takımda.'
            ], 422);
        }

        $team->users()->attach($request->user_id);

        $user = User::find($request->user_id);

        return response()->json([
            'success' => true,
            'message' => 'Üye başarıyla eklendi.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Takımdan üye çıkarır (AJAX).
     */
    public function removeMember(Request $request, Team $team): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // En az 1 üye kalmalı
        if ($team->users()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Takımda en az bir üye kalmalıdır.'
            ], 422);
        }

        $team->users()->detach($request->user_id);

        return response()->json([
            'success' => true,
            'message' => 'Üye başarıyla çıkarıldı.'
        ]);
    }

    /**
     * Kullanıcının takımlarını listeler (AJAX).
     */
    public function getUserTeams(User $user): JsonResponse
    {
        $teams = $user->teams()
            ->with('users')
            ->withCount('users')
            ->get();

        return response()->json([
            'success' => true,
            'teams' => $teams
        ]);
    }
}