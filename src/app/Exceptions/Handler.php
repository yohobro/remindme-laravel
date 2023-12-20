<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {

        if ($e instanceof MissingAbilityException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => $e->getMessage()
            ], 401);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => 'Data not found'
            ], 401);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => $e->getMessage()
            ], 404);
        }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if (auth('sanctum')->check()) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_REFRESH_TOKEN',
                'msg' => 'invalid refresh token'
            ], 401);
        }

        return response()->json([
            'ok' => false,
            'err' => 'ERR_INVALID_CREDS',
            'msg' => $exception->getMessage()
        ], 401);
    }
}
