<?php

namespace App\Modules\Common\V1\Exceptions;

use Exception;

/**
 * Classe base abstrata para exceções de regras de negócio.
 *
 * Esta classe deve ser estendida por todas as exceções que representam
 * falhas relacionadas às regras de negócio da aplicação.
 *
 * Ao lançar uma exceção que estende esta classe, é possível definir
 * mensagens e códigos de status HTTP que serão utilizados para
 * compor respostas padronizadas para a API.
 */
abstract class AbstractBusinessException extends Exception {}
