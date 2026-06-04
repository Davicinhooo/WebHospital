<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    /** @use HasFactory<\Database\Factories\QuotesFactory> */
    use HasFactory;

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    protected $fillable = [
        'date',
        'reason',
        'patient_id',
        'doctor_id',
        'status',
        'observations',
        'room'
    ];
}
