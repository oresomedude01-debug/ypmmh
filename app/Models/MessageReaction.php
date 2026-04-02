<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReaction extends Model
{
    protected $fillable = [
        'program_message_id',
        'user_id',
        'type',
    ];

    public function message()
    {
        return $this->belongsTo(ProgramMessage::class, 'program_message_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
