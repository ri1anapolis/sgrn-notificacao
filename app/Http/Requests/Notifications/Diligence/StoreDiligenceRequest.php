<?php

namespace App\Http\Requests\Notifications\Diligence;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiligenceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'visit_number' => ['required', 'integer', 'min:1', 'max:3'],
            'diligence_result_id' => ['required', 'integer'],
            'observations' => ['nullable', 'string', 'max:65535'],
            'date' => ['required', 'date'],
        ];
    }
}
