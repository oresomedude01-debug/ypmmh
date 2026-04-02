<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'is_free',
        'youtube_url',
        'thumbnail_path',
        'mentor_id',
        'age_target',
        'cohort_age_min',
        'cohort_age_max',
        'start_date',
        'end_date',
        'status',
        'is_featured',
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function contents()
    {
        return $this->hasMany(ProgramContent::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function children()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'program_id', 'user_id')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(ProgramMessage::class);
    }
}
