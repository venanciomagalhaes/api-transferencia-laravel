<?php

namespace App\Modules\Common\V1\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Manipulador personalizado de exceções da aplicação.
 *
 * Esta classe estende o manipulador padrão do Laravel para interceptar
 * exceções específicas de regras de negócio (AbstractBusinessException)
 * e formatar a resposta JSON com a mensagem e o código HTTP adequado.
 *
 * Para exceções que não sejam do tipo AbstractBusinessException,
 * a manipulação padrão do Laravel é mantida.
 */
class AppExceptionHandler extends Handler
{
    /**
     * Renderiza uma exceção em uma resposta HTTP.
     *
     * Intercepta exceções do tipo AbstractBusinessException para retornar
     * uma resposta JSON com a mensagem e o código HTTP apropriado.
     * Para outras exceções, utiliza o comportamento padrão do Laravel.
     *
     * @param  Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof AbstractBusinessException) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }

        return parent::render($request, $e);
    }
}
