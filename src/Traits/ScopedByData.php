<?php

namespace Bpma\DataScope\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait ScopedByData
{
    /**
     * Terapkan filter Data Scope pada model ini.
     * Pemanggilan di Controller: Model::viewable()->get();
     */
    public function scopeViewable(Builder $query, $user = null): Builder
    {
        // Gunakan user yang dilempar, atau ambil user yang sedang login
        $user = $user ?? auth()->user();

        // Keamanan Lapis 1: Tolak akses jika tidak ada user yang login
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        // Ambil string driver dari trait HasDataScopes milik User
        // Asumsi: Model User wajib menggunakan Trait HasDataScopes
        if (!method_exists($user, 'getCurrentScopeDriver')) {
            throw new \Exception('Model User harus menggunakan trait Bpma\DataScope\Traits\HasDataScopes');
        }

        $driverName = $user->getCurrentScopeDriver();

        // Keamanan Lapis 2: Jika user tidak memiliki aturan scope, tolak data (kembalikan data kosong)
        if (!$driverName) {
            return $query->whereRaw('1 = 0');
        }

        // Baca class driver dari config/data-scope.php di aplikasi utama
        $driverClass = config("data-scope.drivers.{$driverName}");

        // Keamanan Lapis 3: Validasi apakah class driver-nya terdaftar dan eksis
        if (!$driverClass || !class_exists($driverClass)) {
            return $query->whereRaw('1 = 0');
        }

        // Resolve class driver (Bisa menampung dependency injection jika ada)
        $driver = App::make($driverClass);

        // Eksekusi fungsi apply() milik driver tersebut ke dalam query ini
        return $driver->apply($query, $user);
    }
}