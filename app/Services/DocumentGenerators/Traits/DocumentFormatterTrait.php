<?php

namespace App\Services\DocumentGenerators\Traits;

use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

trait DocumentFormatterTrait
{
    /**
     * Format a date in extended format (ex: "15 de janeiro de 2026").
     */
    protected function formatDate(TemplateProcessor $t, string $key, $date): void
    {
        if (! $date) {
            $t->setValue($key, '___/___/___');

            return;
        }
        $d = Carbon::parse($date);
        $t->setValue($key, $d->day.' de '.$d->translatedFormat('F').' de '.$d->year);
    }

    /**
     * Format a date in short format (ex: "15/01/2026").
     */
    protected function formatDateShort(TemplateProcessor $t, string $key, $date): void
    {
        if (! $date) {
            $t->setValue($key, '___/___/___');

            return;
        }
        $d = Carbon::parse($date);
        $t->setValue($key, $d->format('d/m/Y'));
    }

    /**
     * Format a currency value (ex: "1.500,00").
     */
    protected function formatCurrency(TemplateProcessor $t, string $key, $value): void
    {
        $val = (float) ($value ?? 0);
        $formatted = number_format($val, 2, ',', '.');
        $t->setValue($key, $formatted);
    }

    /**
     * Check if a person is male based on gender attribute.
     */
    protected function isMale($person): bool
    {
        if (! $person || ! $person->gender) {
            return true;
        }
        $gender = $person->gender instanceof \BackedEnum ? $person->gender->value : $person->gender;

        return in_array(strtolower((string) $gender), ['masculine', 'male', 'm', 'masculino']);
    }

    /**
     * Convert a numeric value to words in Portuguese (ex: 1500.50 -> "um mil e quinhentos reais e cinquenta centavos").
     */
    protected function numberToWords(float $value): string
    {
        if ($value == 0) {
            return 'zero reais';
        }

        $units = ['', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
        $teens = ['dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezessete', 'dezoito', 'dezenove'];
        $tens = ['', '', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa'];
        $hundreds = ['', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];

        $integerPart = (int) floor($value);
        $decimalPart = (int) round(($value - $integerPart) * 100);

        $convertGroup = function (int $n) use ($units, $teens, $tens, $hundreds): string {
            if ($n == 0) {
                return '';
            }

            if ($n == 100) {
                return 'cem';
            }

            $parts = [];

            $h = (int) floor($n / 100);
            $remainder = $n % 100;

            if ($h > 0) {
                $parts[] = $hundreds[$h];
            }

            if ($remainder >= 10 && $remainder <= 19) {
                $parts[] = $teens[$remainder - 10];
            } elseif ($remainder > 0) {
                $t = (int) floor($remainder / 10);
                $u = $remainder % 10;

                if ($t > 0) {
                    $parts[] = $tens[$t];
                }
                if ($u > 0) {
                    $parts[] = $units[$u];
                }
            }

            return implode(' e ', $parts);
        };

        $result = [];

        $originalIntegerPart = $integerPart;

        if ($integerPart >= 1000000) {
            $millions = (int) floor($integerPart / 1000000);
            $integerPart = $integerPart % 1000000;

            if ($millions == 1) {
                $result[] = 'um milhão';
            } else {
                $result[] = $convertGroup($millions).' milhões';
            }
        }

        if ($integerPart >= 1000) {
            $thousands = (int) floor($integerPart / 1000);
            $integerPart = $integerPart % 1000;

            if ($thousands == 1) {
                $result[] = 'um mil';
            } else {
                $result[] = $convertGroup($thousands).' mil';
            }
        }

        if ($integerPart > 0) {
            $result[] = $convertGroup($integerPart);
        }

        $integerText = implode(' e ', array_filter($result));

        if ($originalIntegerPart == 1) {
            $integerText .= ' real';
        } else {
            $integerText .= ' reais';
        }

        if ($decimalPart > 0) {
            $centavosText = $convertGroup($decimalPart);
            if ($decimalPart == 1) {
                $integerText .= ' e '.$centavosText.' centavo';
            } else {
                $integerText .= ' e '.$centavosText.' centavos';
            }
        }

        return $integerText;
    }
}
