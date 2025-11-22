<?php

namespace App\Domains\Booking\Models;

use App\Support\Domain\Shared\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasUuid;

    /**
     * The UUID column name.
     *
     * @var string
     */
    protected $uuidColumn = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'status',
        'changed_by',
        'reason',
    ];

    /**
     * Get the booking that owns the history record.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}