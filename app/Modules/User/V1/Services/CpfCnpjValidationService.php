<?php

namespace App\Modules\User\V1\Services;

class CpfCnpjValidationService
{

    /**
     * Checks if the provided document is a valid CPF.
     *
     * @param string $document The document string to check.
     * @return bool True if valid CPF, false otherwise.
     */
    public function isCpf(string $document): bool
    {
        $numbersOnly = preg_replace('/\D/', '', $document);
        return $this->isValidCpf($numbersOnly);
    }

    /**
     * Checks if the provided document is a valid CNPJ.
     *
     * @param string $document The document string to check.
     * @return bool True if valid CNPJ, false otherwise.
     */
    public function isCnpj(string $document): bool
    {
        $numbersOnly = preg_replace('/\D/', '', $document);
        return $this->isValidCnpj($numbersOnly);
    }

    /**
     * Validates a CPF number.
     *
     * @param string $cpf CPF number containing only digits.
     * @return bool True if valid, false otherwise.
     */
    public function isValidCpf(string $cpf): bool
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates a CNPJ number.
     *
     * @param string $cnpj CNPJ number containing only digits.
     * @return bool True if valid, false otherwise.
     */
    /**
     * Validates a CNPJ number.
     *
     * @param string $cnpj CNPJ number containing only digits.
     * @return bool True if valid, false otherwise.
     */
    public function isValidCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights1[$i];
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights2[$i];
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

        return ($cnpj[12] == $digit1 && $cnpj[13] == $digit2);
    }


}
