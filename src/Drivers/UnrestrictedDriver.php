<?php

namespace Bpma\DataScope\Drivers;

use Bpma\DataScope\Contracts\ScopeDriver;
use Illuminate\Database\Eloquent\Builder;

class UnrestrictedDriver implements ScopeDriver
{
    /**
     * Apply scope unrestricted (tanpa batas).
     * * @param Builder $query
     * @param mixed $user
     * @return Builder
     */
    public function apply(Builder $query, $user): Builder
    {
        return $query;
    }
}