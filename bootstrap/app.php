<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Render exceptions with custom JSON responses
        $exceptions->render(function (Throwable $exception, Request $request) {

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error'   => 'Resource Not Found',
                    'message' => 'موردی با این شناسه یافت نشد.'
                ], 404);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'error'   => 'Route Not Found',
                    'message' => 'مسیر موردنظر یافت نشد.'
                ], 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'error'   => 'Method Not Allowed',
                    'message' => 'متد درخواست‌شده غیرمجاز است.'
                ], 405);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error'   => 'Validation Error',
                    'message' => $exception->validator->errors()
                ], 422);
            }

            return response()->json([
                'error'   => 'Server Error',
                'message' => 'مشکلی در سرور رخ داده است.'
            ], 500);
        });

    })
    ->create();
