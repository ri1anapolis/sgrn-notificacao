<?php

namespace App\Http\Requests\DataProcessing;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class ShowDataProcessingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role == UserRole::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $onlyNumbers = 'regex:/^[0-9]+$/';

        return [
            'protocol' => ['required', $onlyNumbers, 'min:6', 'max:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'protocol.required' => 'O protocolo é obrigatório.',
            'protocol.regex' => 'O protocolo deve conter apenas números (sem pontos, traços ou letras).',
        ];
    }
}
