<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramContent extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'content_type',
        'youtube_url',
        'file_path',
        'target_age',
        'week_number',
        'day_number',
        'week_offset',
        'day_offset',
        'time_of_day',
        'publish_at',
        'is_active',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

}
