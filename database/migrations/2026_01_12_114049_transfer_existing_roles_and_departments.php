<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    public function up()
    {
        // 1. Tüm kullanıcıları çekelim
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // --- ROL TRANSFERİ ---
            if (!empty($user->role)) {
                $roleName = trim($user->role);
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $role->id,
                    'model_type' => 'App\Models\User',
                    'model_id' => $user->id
                ]);
            }

            // --- DEPARTMAN TRANSFERİ (Düzeltildi: Timestamps kaldırıldı) ---
            if (!empty($user->department_id)) {
                DB::table('department_user')->insertOrIgnore([
                    'user_id' => $user->id,
                    'department_id' => $user->department_id,
                    // Zaman damgaları bu tabloda olmadığı için çıkartıldı.
                ]);
            }
        }
    }
    public function down()
    {
        // Bu geri döndürülemez bir işlemdir (Data Migration)
    }
};