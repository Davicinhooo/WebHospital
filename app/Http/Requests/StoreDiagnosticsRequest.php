<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'required|string',
            'date' => 'required|date',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'=> 'required|exists:doctors,id',
            'severity' => 'required|string|max:255',
            'recommendations' => 'nullable|string', 
            'type_diagnosis' => 'required|string|max:255',
        ];
    }
}
