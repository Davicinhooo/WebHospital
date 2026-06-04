<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medications extends Model
{
    /** @use HasFactory<\Database\Factories\MedicationsFactory> */
    use HasFactory;

    public function treatment()
    {
        return $this->belongsTo(Treatments::class, 'treatment_id');
    }

    protected $fillable = [
        'name',
        "dose",
        "frequency",
        "duration",
        "treatment_id",
        "supplier",
        "side_effects"
    ];
}
