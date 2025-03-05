<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ReservationException;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function index()
    {
        return ApiResponse::success(
            ReservationResource::collection($this->reservationRepository->all()),
            'Reservations retrieved successfully'
        );
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = $this->reservationRepository->create($request->validated());

        if (!$reservation) {
            return ApiResponse::error('This edition is not available for reservation', Response::HTTP_CONFLICT);
        }

        return ApiResponse::success(new ReservationResource($reservation), 'Reservation created successfully', Response::HTTP_CREATED);
    }

    public function cancel(Reservation $reservation)
    {
        if (!$this->reservationRepository->cancel($reservation)) {
            return ApiResponse::error('Reservation cannot be cancelled', Response::HTTP_CONFLICT);
        }

        return ApiResponse::success([], 'Reservation cancelled successfully');
    }

    public function returnBook(Reservation $reservation)
    {
        if ($this->reservationRepository->returnBook($reservation)) {
            return ApiResponse::success([], 'Book returned successfully');
        }

        return ApiResponse::error('Error processing return', Response::HTTP_BAD_REQUEST);
    }
}
