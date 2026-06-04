<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostics extends Model
{
    /** @use HasFactory<\Database\Factories\DiagnosticsFactory> */
    use HasFactory;

    public function treatment()
    {
        return $this->hasMany(Treatments::class, 'diagnostic_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

}
