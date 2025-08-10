<?php

namespace App\Modules\User\V1\Services;

/**
 * Serviço responsável pela validação de documentos brasileiros: CPF e CNPJ.
 */
class CpfCnpjValidationService
{
    /**
     * Verifica se o documento informado é um CPF válido.
     *
     * Remove qualquer caractere não numérico antes da validação.
     *
     * @param  string  $document  Documento a ser validado.
     * @return bool Retorna true se for um CPF válido, false caso contrário.
     */
    public function isCpf(string $document): bool
    {
        $numbersOnly = preg_replace('/\D/', '', $document);

        return $this->isValidCpf($numbersOnly);
    }

    /**
     * Verifica se o documento informado é um CNPJ válido.
     *
     * Remove qualquer caractere não numérico antes da validação.
     *
     * @param  string  $document  Documento a ser validado.
     * @return bool Retorna true se for um CNPJ válido, false caso contrário.
     */
    public function isCnpj(string $document): bool
    {
        $numbersOnly = preg_replace('/\D/', '', $document);

        return $this->isValidCnpj($numbersOnly);
    }

    /**
     * Valida um número de CPF.
     *
     * O CPF deve conter exatamente 11 dígitos e não pode ser uma sequência repetida (ex: 111.111.111-11).
     * A validação é feita com base nos dois dígitos verificadores finais.
     *
     * @param  string  $cpf  Número do CPF contendo apenas dígitos.
     * @return bool Retorna true se o CPF for válido, false caso contrário.
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
     * Valida um número de CNPJ.
     *
     * O CNPJ deve conter exatamente 14 dígitos e não pode ser uma sequência repetida (ex: 00.000.000/0000-00).
     * A validação é feita com base nos dois dígitos verificadores finais.
     *
     * @param  string  $cnpj  Número do CNPJ contendo apenas dígitos.
     * @return bool Retorna true se o CNPJ for válido, false caso contrário.
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

        return $cnpj[12] == $digit1 && $cnpj[13] == $digit2;
    }
}
