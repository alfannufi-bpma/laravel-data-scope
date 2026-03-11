<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataScope extends Model
{
    // Sesuaikan dengan nama tabel utama scope di package-mu
    protected $table = 'data_scopes'; 
    
    protected $fillable = ['name', 'driver']; // Sesuaikan kolomnya
}