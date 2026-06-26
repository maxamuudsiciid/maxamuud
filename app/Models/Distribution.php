<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id', 'hospital_id', 'blood_group',
        'quantity', 'distribution_date', 'approved_by'
    ];

    protected $casts = [
        'distribution_date' => 'date',
    ];

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
