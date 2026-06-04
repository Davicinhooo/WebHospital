<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'date' => $this->date,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'severity' => $this->severity,
            'recommendations' => $this->recommendations,
            'type_diagnosis' => $this->type_diagnosis,
        ];
    }
}
