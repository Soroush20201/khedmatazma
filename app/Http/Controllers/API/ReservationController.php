<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        return response()->json(Reservation::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'edition_id' => 'required|exists:editions,id',
        ]);

        $edition = Edition::find($request->edition_id);

        if (!$edition->available) {
            return response()->json(['message' => 'This edition is not available'], Response::HTTP_CONFLICT);
        }

        $reservation = Reservation::create([
            'user_id' => $request->user_id,
            'edition_id' => $request->edition_id,
            'reserved_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(14),
            'status' => 'approved'
        ]);

        $edition->update(['available' => false]);

        return response()->json($reservation, Response::HTTP_CREATED);
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->status != 'approved') {
            return response()->json(['message' => 'Reservation is not active'], Response::HTTP_CONFLICT);
        }

        $reservation->update(['status' => 'cancelled']);
        $reservation->edition->update(['available' => true]);

        return response()->json(['message' => 'Reservation cancelled successfully'], Response::HTTP_OK);
    }

    public function returnBook(Reservation $reservation)
    {
        if ($reservation->status != 'approved') {
            return response()->json(['message' => 'Reservation is not active'], Response::HTTP_CONFLICT);
        }

        $reservation->update(['status' => 'returned']);
        $reservation->edition->update(['available' => true]);

        return response()->json(['message' => 'Book returned successfully'], Response::HTTP_OK);
    }
}
