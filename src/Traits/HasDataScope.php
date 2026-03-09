<?php

namespace Bpma\DataScope\Traits;

use Illuminate\Support\Facades\DB;

trait HasDataScopes
{
    /**
     * Mengambil string driver dari scope yang aktif untuk user ini.
     * (Contoh output: 'hierarchy', 'all', atau null)
     */
    public function getCurrentScopeDriver(): ?string
    {
        // 1. Cek scope yang melekat LANGSUNG pada User ini (Prioritas Utama)
        $directScope = DB::table('model_has_data_scopes')
            ->join('data_scopes', 'model_has_data_scopes.data_scope_id', '=', 'data_scopes.id')
            ->where('model_type', static::class) // Nama class model User saat ini
            ->where('model_id', $this->id)
            ->value('data_scopes.driver');

        if ($directScope) {
            return $directScope;
        }

        // 2. Jika tidak ada, cek scope yang melekat pada Role Spatie
        // (Kita cek apakah trait HasRoles milik Spatie terpasang di model ini)
        if (method_exists($this, 'roles')) {
            
            $roleIds = $this->roles->pluck('id')->toArray();

            if (!empty($roleIds)) {
                $roleScope = DB::table('model_has_data_scopes')
                    ->join('data_scopes', 'model_has_data_scopes.data_scope_id', '=', 'data_scopes.id')
                    // Membaca nama class model Role Spatie dari config bawaannya
                    ->where('model_type', config('permission.models.role')) 
                    ->whereIn('model_id', $roleIds)
                    ->value('data_scopes.driver');

                if ($roleScope) {
                    return $roleScope;
                }
            }
        }

        // 3. Fallback keamanan: Jika tidak punya scope sama sekali
        return null;
    }
}