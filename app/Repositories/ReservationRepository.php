<?php
namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Edition;
use Carbon\Carbon;

class ReservationRepository
{
    public function all()
    {
        return Reservation::with('user', 'edition')->get();
    }

    public function create(array $data)
    {
        $edition = Edition::find($data['edition_id']);

        if (!$edition->available) {
            return null;
        }

        $reservation = Reservation::create([
            'user_id'    => $data['user_id'],
            'edition_id' => $data['edition_id'],
            'reserved_at' => Carbon::now(),
            'due_date'   => Carbon::now()->addDays(14),
            'status'     => 'approved',
        ]);

        $edition->update(['available' => false]);

        return $reservation;
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->status != 'approved') {
            return false;
        }

        $reservation->update(['status' => 'cancelled']);
        $reservation->edition->update(['available' => true]);

        return true;
    }

    public function returnBook(Reservation $reservation)
    {
        $daysOverdue = now()->diffInDays($reservation->due_date, false);

        if ($daysOverdue > 0) {
            $reservation->user->notify(new \App\Notifications\ReservationNotification("شما {$daysOverdue} روز تأخیر داشته‌اید. جریمه ثبت شده است."));
        }

        $reservation->update(['status' => 'returned']);
        $reservation->edition->update(['available' => true]);

        return true;
    }
}
