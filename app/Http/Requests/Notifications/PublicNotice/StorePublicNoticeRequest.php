<?php

namespace App\Http\Requests\Notifications\PublicNotice;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicNoticeRequest extends FormRequest
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
            'publication_organ' => ['required', 'string', 'max:255'],
            'days_between_email_and_notice' => ['nullable', 'integer', 'min:0'],
            'publications' => ['nullable', 'array', 'max:5'],
            'publications.*.publication_order' => ['required', 'integer', 'min:1', 'max:5'],
            'publications.*.edition' => ['nullable', 'string', 'max:50'],
            'publications.*.notice_number' => ['nullable', 'string', 'max:50'],
            'publications.*.publication_date' => ['nullable', 'date'],
        ];
    }
}
