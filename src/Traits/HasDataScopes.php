<?php

namespace Bpma\DataScope\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Bpma\DataScope\Models\DataScope;

trait HasDataScopes
{
    /**
     * A model may have multiple data scopes.
     */
    public function dataScopes(): BelongsToMany
    {
        return $this->morphToMany(
            DataScope::class,
            'model',
            'model_has_data_scopes',
            'model_id',
            'data_scope_id'
        );
    }

    /**
     * Remove all current data scopes and set the given ones.
     */
    public function syncDataScopes(array $dataScopeIds): static
    {
        $this->dataScopes()->sync($dataScopeIds);

        return $this;
    }

    /**
     * Get the active scope driver for the model.
     * Priority: user's direct scope > role's scope > null
     */
    public function resolveDriver(): ?string
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
                $roleScope = DB::table('model_has_data_scopes')
                    ->join('data_scopes', 'model_has_data_scopes.data_scope_id', '=', 'data_scopes.id')
                    ->where('model_type', config('permission.models.role'))
                    ->whereIn('model_id', $roleIds)
                    ->value('data_scopes.driver');

                if ($roleScope) {
                    return $roleScope;
                }
            }
        }

        return null;
    }
}