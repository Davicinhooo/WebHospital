<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatments extends Model
{
    /** @use HasFactory<\Database\Factories\TreatmentsFactory> */
    use HasFactory;

    public function medication()
    {
        return $this->hasMany(Medications::class, 'treatment_id');
    }

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostics::class, 'diagnostic_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    protected $fillable = [
        "name",
        "description",
        "duration",
        "diagnostic_id",
        "doctor_id",
        "status",
        "administration_frequency"
    ];
}
