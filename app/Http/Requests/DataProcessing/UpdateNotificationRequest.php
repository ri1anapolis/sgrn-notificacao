<?php

namespace App\Http\Requests\DataProcessing;

use App\Enums\NotifiedPersonGender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNotificationRequest extends FormRequest
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
        $notifiableType = $this->input('notifiable.notifiable_type');

        $notifiableRules = [
            'notifiable' => ['nullable', 'array'],
            'notifiable.id' => ['nullable', 'integer'],
            'notifiable.notifiable_type' => [
                'nullable',
                'string',
                Rule::in([
                    'App\Models\AlienationRealEstate',
                    'App\Models\AlienationMovableProperty',
                    'App\Models\PurchaseAndSaleSubdivision',
                    'App\Models\PurchaseAndSaleIncorporation',
                    'App\Models\RetificationArea',
                    'App\Models\Adjudication',
                    'App\Models\AdversePossession',
                    'App\Models\Other',
                ]),
            ],
        ];

        if ($notifiableType === 'App\Models\AlienationRealEstate') {
            $notifiableRules = array_merge($notifiableRules, [

                'notifiable.creditor' => ['required', 'string', 'max:255'],
                'notifiable.office' => ['nullable', 'integer'],
                'notifiable.guarantee_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.guarantee_property_address' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_registration_act' => ['nullable', 'string', 'max:255'],
                'notifiable.emoluments_intimation' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_number' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_date' => ['nullable', 'date'],
                'notifiable.total_amount_debt' => ['nullable', 'numeric'],
                'notifiable.debt_position_date' => ['nullable', 'date'],
                'notifiable.default_period' => ['nullable', 'string', 'max:255'],
                'notifiable.grace_period' => ['required', 'boolean'],
                'notifiable.contractual_clause' => ['nullable', 'string'],
                'notifiable.real_estate_registry_location' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\AlienationMovableProperty') {
            $notifiableRules = array_merge($notifiableRules, [

                'notifiable.creditor' => ['required', 'string', 'max:255'],
                'notifiable.office' => ['nullable', 'integer'],
                'notifiable.guarantee_movable_property_description' => ['nullable', 'string'],
                'notifiable.contract_registry_data' => ['nullable', 'string', 'max:255'],
                'notifiable.emoluments_intimation' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_number' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_date' => ['nullable', 'date'],
                'notifiable.total_amount_debt' => ['nullable', 'numeric'],
                'notifiable.debt_position_date' => ['nullable', 'date'],
                'notifiable.default_period' => ['nullable', 'string', 'max:255'],
                'notifiable.grace_period' => ['required', 'boolean'],
                'notifiable.contractual_clause' => ['nullable', 'string'],
                'notifiable.contract_registry_office' => ['nullable', 'string', 'max:225'],
            ]);
        } elseif ($notifiableType === 'App\Models\PurchaseAndSaleSubdivision') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.creditor' => ['required', 'string', 'max:255'],
                'notifiable.office' => ['nullable', 'integer'],
                'notifiable.committed_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.committed_property_identification' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_registration_act' => ['nullable', 'string', 'max:255'],
                'notifiable.emoluments_intimation' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_number' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_date' => ['nullable', 'date'],
                'notifiable.total_amount_debt' => ['nullable', 'numeric'],
                'notifiable.debt_position_date' => ['nullable', 'date'],
                'notifiable.default_period' => ['nullable', 'string', 'max:255'],
                'notifiable.grace_period' => ['required', 'boolean'],
                'notifiable.contractual_clause' => ['nullable', 'string'],
                'notifiable.real_estate_registry_location' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\PurchaseAndSaleIncorporation') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.creditor' => ['required', 'string', 'max:255'],
                'notifiable.office' => ['nullable', 'integer'],
                'notifiable.committed_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.committed_property_identification' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_registration_act' => ['nullable', 'string', 'max:255'],
                'notifiable.emoluments_intimation' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_number' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_date' => ['nullable', 'date'],
                'notifiable.total_amount_debt' => ['nullable', 'numeric'],
                'notifiable.debt_position_date' => ['nullable', 'date'],
                'notifiable.default_period' => ['nullable', 'string', 'max:255'],
                'notifiable.grace_period' => ['required', 'boolean'],
                'notifiable.contractual_clause' => ['nullable', 'string'],
                'notifiable.real_estate_registry_location' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\RetificationArea') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.office' => ['required', 'integer'],
                'notifiable.rectifying_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.rectifying_property_identification' => ['nullable', 'string', 'max:255'],
                'notifiable.rectifying_property_registry_office' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\Adjudication') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.office' => ['required', 'integer'],
                'notifiable.adjudicated_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.adjudicated_property_identification' => ['nullable', 'string', 'max:255'],
                'notifiable.adjudicated_property_registry_office' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\AdversePossession') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.office' => ['required', 'integer'],
                'notifiable.adverse_possession_property_registration' => ['required', 'string', 'max:255'],
                'notifiable.adverse_possession_property_identification' => ['required', 'string', 'max:255'],
                'notifiable.adverse_possession_property_registry_office' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($notifiableType === 'App\Models\Other') {
            $notifiableRules = array_merge($notifiableRules, [
                'notifiable.creditor' => ['required', 'string', 'max:255'],
                'notifiable.office' => ['required', 'integer'],
                'notifiable.guarantee_property_registration' => ['nullable', 'string', 'max:255'],
                'notifiable.guarantee_property_address' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_registration_act' => ['nullable', 'string', 'max:255'],
                'notifiable.emoluments_intimation' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_number' => ['nullable', 'string', 'max:255'],
                'notifiable.contract_date' => ['nullable', 'date'],
                'notifiable.total_amount_debt' => ['nullable', 'numeric'],
                'notifiable.debt_position_date' => ['nullable', 'date'],
                'notifiable.default_period' => ['nullable', 'string', 'max:255'],
                'notifiable.grace_period' => ['required', 'boolean'],
                'notifiable.contractual_clause' => ['nullable', 'string'],
                'notifiable.real_estate_registry_location' => ['nullable', 'string', 'max:255'],
            ]);
        }

        return array_merge(
            [
                'notified_people' => ['present', 'array'],
                'notified_people.*.id' => ['nullable', 'integer', 'exists:notified_people,id'],
                'notified_people.*.name' => ['required', 'string', 'max:255'],
                'notified_people.*.document' => ['required', 'string', 'max:20'],
                'notified_people.*.phone' => ['nullable', 'string', 'max:20'],
                'notified_people.*.email' => ['nullable', 'email', 'max:255'],
                'notified_people.*.gender' => ['required', Rule::enum(NotifiedPersonGender::class)],

                'addresses' => ['present', 'array'],
                'addresses.*.id' => ['nullable', 'integer', 'exists:addresses,id'],
                'addresses.*.full_address' => ['required', 'string', 'max:255'],
            ],
            $notifiableRules
        );
    }
}
