<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    // Table name (optional — Laravel maps ForumPost → forum_posts automatically)
    protected $table = 'forum_posts';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable columns
    protected $fillable = [
        'thread_id',
        'posted_by',
        'body',
        'is_answer',
    ];

    public $timestamps = true;

    // ---------------------------------------------
    // 🔗 Relationships
    // ---------------------------------------------

    // Each post belongs to a thread
    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    // Each post belongs to a user (who posted it)
    public function author()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
