<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'child_id',
        'program_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway_reference',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
