<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'blood_group', 'quantity',
        'donation_date', 'expiry_date', 'screening_result'
    ];

    protected $casts = [
        'donation_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function bloodTest()
    {
        return $this->hasOne(BloodTest::class);
    }
}
