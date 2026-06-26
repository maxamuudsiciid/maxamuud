<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'appointment_date',
        'appointment_time',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        // appointment_time will come as H:i:s or H:i, we can leave it as string or cast if needed
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
