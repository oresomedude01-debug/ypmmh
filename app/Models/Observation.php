<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $fillable = [
        'mentor_id',
        'child_id',
        'content',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id');
    }
}
