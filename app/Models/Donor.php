<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'user_id', 'full_name', 'gender', 'date_of_birth',
        'blood_group', 'phone', 'email', 'address',
        'last_donation_date', 'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_donation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodCollections()
    {
        return $this->hasMany(BloodCollection::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
