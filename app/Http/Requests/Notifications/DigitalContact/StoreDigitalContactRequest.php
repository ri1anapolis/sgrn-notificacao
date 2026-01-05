<?php

namespace App\Http\Requests\Notifications\DigitalContact;

use Illuminate\Foundation\Http\FormRequest;

class StoreDigitalContactRequest extends FormRequest
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
            'contact_date' => ['required', 'date'],
            'contact_time' => ['required', 'date_format:H:i'],
            'whatsapp_result' => ['nullable', 'required_without_all:email_result,custom_result', 'string', 'max:255'],
            'email_result' => ['nullable', 'required_without_all:whatsapp_result,custom_result', 'string', 'max:255'],
            'custom_result' => ['nullable', 'required_without_all:whatsapp_result,email_result', 'string', 'max:1000'],
        ];
    }
}
