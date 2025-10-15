<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Table name (optional — Laravel automatically maps "Setting" → "settings")
    protected $table = 'settings';

    // Primary key (default is 'id')
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'key',
        'value',
    ];

    // Enable timestamps (created_at, updated_at)
    public $timestamps = true;
}
