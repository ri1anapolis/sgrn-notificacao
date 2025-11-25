<?php

namespace App\Http\Requests\DataProcessing;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreProtocolRequest extends FormRequest
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
            'protocol' => ['required', $onlyNumbers, 'max:20'],
        ];
    }
}
