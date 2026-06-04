<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentsResource extends JsonResource
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
            "name" => $this->name,
            "description" => $this->description,
            "duration" => $this->duration,
            "diagnostic" => $this->diagnostic,
            "doctor_id" => $this->doctor_id,
            "status" => $this->status,
            "administration_frequency" => $this->administration_frequency,
        ];
    }
}
