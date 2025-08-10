<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Resposta JSON para operações bem-sucedidas
     */
    protected function successResponse($data = null, string $message = 'Operação realizada com sucesso', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        // Sanitiza dados sensíveis antes de retornar
        $sanitizedData = $this->sanitizeData($data);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $sanitizedData,
        ], $statusCode);
    }

    /**
     * Resposta JSON para erros
     */
    protected function errorResponse(string $message = 'Ocorreu um erro', int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        // Log do erro para monitoramento
        Log::error($message, [
            'status' => $statusCode,
            'errors' => $errors,
            'user' => auth()->id() ?? 'guest'
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Resposta para recurso não encontrado
     */
    protected function notFoundResponse(string $message = 'Recurso não encontrado'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Resposta para acesso não autorizado
     */
    protected function unauthorizedResponse(string $message = 'Acesso não autorizado'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Resposta para erros de validação
     */
    protected function validationErrorResponse(array $errors, string $message = 'Erros de validação'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Verifica se a requisição é para API
     */
    protected function isApiRequest(): bool
    {
        return request()->expectsJson() || request()->is('api/*');
    }

    /**
     * Redirecionamento com mensagem de sucesso
     */
    protected function redirectWithSuccess(string $route, string $message = 'Operação realizada com sucesso', $parameters = [])
    {
        return redirect()
            ->route($route, $parameters)
            ->with('success', $message);
    }

    /**
     * Redirecionamento com mensagem de erro
     */
    protected function redirectWithError(string $route, string $message = 'Ocorreu um erro', $parameters = [])
    {
        return redirect()
            ->route($route, $parameters)
            ->with('error', $message);
    }

    /**
     * Sanitiza dados sensíveis antes do retorno
     */
    protected function sanitizeData($data)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, ['password', 'token', 'api_key', 'credit_card'])) {
                    $data[$key] = '*****';
                }
            }
        }
        return $data;
    }

    /**
     * Obtém o usuário autenticado com verificação segura
     */
    protected function getAuthenticatedUser()
    {
        try {
            return auth()->userOrFail();
        } catch (\Exception $e) {
            Log::warning('Tentativa de acessar usuário não autenticado');
            return null;
        }
    }
}