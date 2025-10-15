<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Table name (optional since Laravel automatically uses plural form)
    protected $table = 'services';

    // Primary key (default is 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // Enable timestamps (created_at and updated_at)
    public $timestamps = true;
}

