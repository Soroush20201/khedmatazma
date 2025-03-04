<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PenaltyException extends Exception
{
    public function report(): void
    {
        \Log::error("Penalty Error: " . $this->getMessage());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error'   => 'Penalty Error',
            'message' => $this->getMessage()
        ], Response::HTTP_BAD_REQUEST);
    }
}
