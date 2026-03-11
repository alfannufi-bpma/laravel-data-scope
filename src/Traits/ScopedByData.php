<?php

namespace Bpma\DataScope\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait ScopedByData
{
    public function scopeViewable(Builder $query, $user = null): Builder
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if (!method_exists($user, 'getCurrentScopeDriver')) {
            throw new \Exception('Model User harus menggunakan trait Bpma\DataScope\Traits\HasDataScopes');
        }

        $driverName = $user->getCurrentScopeDriver();
        if (!$driverName) {
            return $query->whereRaw('1 = 0');
        }

        $driverClass = config("data-scope.drivers.{$driverName}");
        if (!$driverClass || !class_exists($driverClass)) {
            return $query->whereRaw('1 = 0');
        }

        $driver = App::make($driverClass);
        return $driver->apply($query, $user);
    }
}