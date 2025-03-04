<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReservationException extends Exception
{
    public function report(): void
    {
        // Log the error (Optional)
        \Log::error("Reservation Error: " . $this->getMessage());
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error'   => 'Reservation Error',
            'message' => $this->getMessage()
        ], Response::HTTP_CONFLICT);
    }
}
