<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorsFactory> */
    use HasFactory;

    public function diagnostics()
    {
        return $this->hasMany(Diagnostics::class, 'doctor_id');
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'specialty',
        'phone',
        'email',
        'license',
        'years_of_experience',
    ];
}
