<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
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
    }
}
