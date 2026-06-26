<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id', 'patient_name', 'blood_group',
        'quantity', 'request_date', 'status', 'urgency_level'
    ];
    

    protected $casts = [
        'request_date' => 'date',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function distribution()
    {
        return $this->hasOne(Distribution::class);
    }
}
