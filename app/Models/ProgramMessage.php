<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramMessage extends Model
{
    protected $fillable = [
        'program_id',
        'user_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }
}
