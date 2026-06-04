<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotesResource extends JsonResource
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
            "date" => $this->date,
            'reason' => $this->reason,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'status' => $this->status,
            'observations' => $this->observations,
            'room' => $this->room,
        ];
    }
}
