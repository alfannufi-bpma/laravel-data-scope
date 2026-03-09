<?php

namespace Bpma\DataScope\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ScopeDriver
{
    /**
     * @param Builder $query Query builder dari model
     * @param mixed $user User yang sedang login
     */
    public function apply(Builder $query, $user): Builder;
}