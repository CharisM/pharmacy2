<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'subject', 'body',
        'admin_read', 'user_read', 'has_unread_reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(MessageReply::class)->orderBy('created_at');
    }
}
