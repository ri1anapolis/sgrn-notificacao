<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;

class ShowNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $onlyNumbers = 'regex:/^[0-9]+$/';

        return [
            'protocol' => ['required', $onlyNumbers],
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
