<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataScope extends Model
{
    protected $table = 'data_scopes'; 
    
    protected $fillable = ['name', 'driver'];
}