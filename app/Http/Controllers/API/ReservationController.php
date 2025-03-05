<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ReservationException;
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
        return ReservationResource::collection($this->reservationRepository->all());
    }

    public function store(StoreReservationRequest $request)
    {
        try {
            $reservation = $this->reservationRepository->create($request->validated());

            if (!$reservation) {
                throw new ReservationException("This edition is not available for reservation.");
            }

            $reservation->user->notify(new \App\Notifications\ReservationNotification("کتاب شما با موفقیت رزرو شد!"));

            return new ReservationResource($reservation);
        } catch (ReservationException $e) {
            return $e->render();
        } catch (\Exception $e) {
            Log::error('Reservation Error: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Reservation Failed',
                'message' => 'مشکلی در رزرو کتاب رخ داده است.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
