<?php

namespace App\Domains\Booking\Models;

use App\Support\BaseModel;
use App\Domains\Identity\Models\User;

class BookingStatusHistory extends BaseModel
{

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'status',
        'changed_by',
        'notes',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
