<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $tableNames = config('data-scope.table_names');

        Schema::create($tableNames['data_scopes'], function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('driver');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_data_scopes'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('data_scope_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->index(['model_id', 'model_type'], 'model_has_data_scopes_model_id_model_type_index');

            $table->foreign('data_scope_id')
                  ->references('id')
                  ->on($tableNames['data_scopes'])
                  ->onDelete('cascade');
                  
            $table->primary(['data_scope_id', 'model_id', 'model_type'], 'model_has_data_scopes_primary');
        });

        DB::table($tableNames['data_scopes'])->insert([
            ['name' => 'Unrestricted', 'driver' => 'unrestricted', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Subordinate',  'driver' => 'subordinate',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        $tableNames = config('data-scope.table_names');
        Schema::dropIfExists($tableNames['model_has_data_scopes']);
        Schema::dropIfExists($tableNames['data_scopes']);
    }
};