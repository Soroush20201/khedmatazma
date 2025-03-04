<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Response;

class ReservationController extends Controller
{
    protected $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function index()
    {
        return ReservationResource::collection($this->reservationRepository->all());
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = $this->reservationRepository->create($request->validated());

        if (!$reservation) {
            return response()->json(['message' => 'This edition is not available'], Response::HTTP_CONFLICT);
        }

        $reservation->user->notify(new \App\Notifications\ReservationNotification("کتاب شما با موفقیت رزرو شد!"));

        return new ReservationResource($reservation);
    }

    public function cancel(Reservation $reservation)
    {
        if (!$this->reservationRepository->cancel($reservation)) {
            return response()->json(['message' => 'Reservation is not active'], Response::HTTP_CONFLICT);
        }

        return response()->json(['message' => 'Reservation cancelled successfully'], Response::HTTP_OK);
    }

    public function returnBook(Reservation $reservation)
    {
        if ($this->reservationRepository->returnBook($reservation)) {
            return response()->json(['message' => 'Book returned successfully'], Response::HTTP_OK);
        }

        return response()->json(['message' => 'Error processing return'], Response::HTTP_BAD_REQUEST);
    }
}
