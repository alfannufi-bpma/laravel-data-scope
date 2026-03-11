<?php

namespace Bpma\DataScope\Traits;

use Illuminate\Support\Facades\DB;

trait HasDataScopes
{
    public function dataScopes()
    {
        return $this->morphToMany(
            DataScope::class,
            'model',
            'model_has_data_scopes',
            'model_id',
            'data_scope_id'
        );
    }

    public function syncScopes(array $dataScopeIds): void
    {
        $this->dataScopes()->sync($dataScopeIds);
    }

    public function getCurrentScopeDriver(): ?string
    {
        $directScope = DB::table('model_has_data_scopes')
            ->join('data_scopes', 'model_has_data_scopes.data_scope_id', '=', 'data_scopes.id')
            ->where('model_type', static::class)
            ->where('model_id', $this->id)
            ->value('data_scopes.driver');

        if ($directScope) {
            return $directScope;
        }

        if (method_exists($this, 'roles')) {
            
            $roleIds = $this->roles->pluck('id')->toArray();

            if (!empty($roleIds)) {
                $scope = DB::table('model_has_data_scopes')
                    ->join('data_scopes', 'model_has_data_scopes.data_scope_id', '=', 'data_scopes.id')
                    ->where('model_type', config('permission.models.role')) 
                    ->whereIn('model_id', $roleIds)
                    ->value('data_scopes.driver');

                if ($scope) {
                    return $scope;
                }
            }
        }

        return null;
    }
}