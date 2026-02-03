<?php

namespace App\Services\DocumentGenerators\Traits;

/**
 * Trait for mapping nature-specific field names across different notification types.
 *
 * This trait provides centralized methods to retrieve fields that vary by nature type,
 * ensuring consistent field access across all document generators.
 */
trait NatureFieldMapperTrait
{
    /**
     * Get the nature name in Portuguese.
     */
    protected function getNatureName(string $type): string
    {
        return match (class_basename($type)) {
            'AlienationRealEstate' => 'Alienação Fiduciária de Bem Imóvel',
            'AlienationMovableProperty' => 'Alienação Fiduciária de Bem Móvel',
            'PurchaseAndSaleSubdivision', 'PurchaseAndSaleIncorporation' => 'Compromisso de Compra e Venda',
            'RetificationArea' => 'Retificação de Área',
            'Adjudication' => 'Adjudicação',
            'AdversePossession' => 'Usucapião',
            default => 'Notificação',
        };
    }

    /**
     * Get the property address/description based on nature type.
     */
    protected function getPropertyAddress($data): string
    {
        return $data->guarantee_property_address ??
               $data->guarantee_movable_property_description ??
               $data->committed_property_identification ??
               $data->rectifying_property_identification ??
               $data->adjudicated_property_identification ??
               $data->adverse_possession_property_identification ??
               '';
    }

    /**
     * Get the registry office location based on nature type.
     */
    protected function getRegistryOffice($data, string $type): string
    {
        if (! $data) {
            return '';
        }

        return match (class_basename($type)) {
            'AlienationRealEstate',
            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation',
            'Other' => $data->real_estate_registry_location ?? '',

            'AlienationMovableProperty' => $data->contract_registry_office ?? '',
            'AdversePossession' => $data->adverse_possession_property_registry_office ?? '',
            'Adjudication' => $data->adjudicated_property_registry_office ?? '',
            'RetificationArea' => $data->rectifying_property_registry_office ?? '',

            default => '',
        };
    }

    /**
     * Get the property registration number (matrícula) based on nature type.
     */
    protected function getPropertyRegistry($data, string $type): string
    {
        if (! $data) {
            return '';
        }

        return match (class_basename($type)) {
            'AlienationRealEstate', 'Other' => $data->guarantee_property_registration ?? '',

            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation' => $data->committed_property_registration ?? '',

            'RetificationArea' => $data->rectifying_property_registration ?? '',

            'Adjudication' => $data->adjudicated_property_registration ?? '',

            'AdversePossession' => $data->adverse_possession_property_registration ?? '',

            'AlienationMovableProperty' => '',

            default => '',
        };
    }

    /**
     * Get the contract number based on nature type.
     */
    protected function getContractNumber($data, string $type): string
    {
        if (! $data) {
            return '';
        }

        return match (class_basename($type)) {
            'AlienationRealEstate',
            'AlienationMovableProperty',
            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation',
            'Other' => $data->contract_number ?? '',

            'RetificationArea',
            'Adjudication',
            'AdversePossession' => '',

            default => '',
        };
    }

    /**
     * Get the contract registration act based on nature type.
     */
    protected function getContractRegistrationAct($data, string $type): string
    {
        if (! $data) {
            return '';
        }

        return match (class_basename($type)) {
            'AlienationRealEstate',
            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation',
            'Other' => $data->contract_registration_act ?? '',

            'AlienationMovableProperty',
            'RetificationArea',
            'Adjudication',
            'AdversePossession' => '',

            default => '',
        };
    }

    /**
     * Get the total amount of debt based on nature type.
     */
    protected function getTotalAmountDebt($data, string $type)
    {
        if (! $data) {
            return 0;
        }

        return match (class_basename($type)) {
            'AlienationRealEstate',
            'AlienationMovableProperty',
            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation',
            'Other' => $data->total_amount_debt ?? 0,

            'RetificationArea',
            'Adjudication',
            'AdversePossession' => 0,

            default => 0,
        };
    }

    /**
     * Get the debt position date based on nature type.
     */
    protected function getDebtPositionDate($data, string $type): ?string
    {
        if (! $data) {
            return null;
        }

        return match (class_basename($type)) {
            'AlienationRealEstate',
            'AlienationMovableProperty',
            'PurchaseAndSaleSubdivision',
            'PurchaseAndSaleIncorporation',
            'Other' => $data->debt_position_date ?? null,

            'RetificationArea',
            'Adjudication',
            'AdversePossession' => null,

            default => null,
        };
    }
}
