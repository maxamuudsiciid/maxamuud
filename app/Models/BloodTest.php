<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_collection_id',
        'test_date',
        'hiv_result',
        'hbv_result',
        'hcv_result',
        'syphilis_result',
        'notes'
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    public function bloodCollection()
    {
        return $this->belongsTo(BloodCollection::class);
    }
}
