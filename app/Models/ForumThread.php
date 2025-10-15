<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    use HasFactory;

    // Optional (Laravel auto maps ForumThread → forum_threads)
    protected $table = 'forum_threads';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'service_id',
        'created_by',
        'is_locked',
    ];

    public $timestamps = true;

    // ---------------------------------------------
    // 🔗 Relationships
    // ---------------------------------------------

    // Each thread belongs to one service (optional)
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Each thread was created by one user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Each thread has many posts
    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }
}
