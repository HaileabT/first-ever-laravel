<?php

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        }

        if ($exception instanceof \InvalidArgumentException) {
            return response()->json([
                'error' => 'Invalid argument provided',
            ], 400);
        }

        // fallback to default
        return parent::render($request, $exception);
    }
}
