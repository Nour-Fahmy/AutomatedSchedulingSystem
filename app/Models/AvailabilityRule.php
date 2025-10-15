<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilityRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'service_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    // ---------------------------------------------
    // 🔗 Relationships
    // ---------------------------------------------

    /**
     * Get the faculty (user) who owns this rule.
     */
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Get the service associated with this rule.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
