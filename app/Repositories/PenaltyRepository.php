<?php
namespace App\Repositories;

use App\Models\Penalty;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenaltyRepository
{
    public function getAll()
    {
        return Penalty::with('user')->get();
    }

    public function applyPenalties()
    {
        $overdueReservations = Reservation::where('status', 'approved')
            ->where('due_date', '<', Carbon::now())
            ->get();

        DB::transaction(function () use ($overdueReservations) {
            foreach ($overdueReservations as $reservation) {
                $daysOverdue = Carbon::now()->diffInDays($reservation->due_date);
                $fineAmount = $daysOverdue * 1000; // هر روز تأخیر ۱۰۰۰ تومان جریمه

                Penalty::create([
                    'user_id' => $reservation->user_id,
                    'amount'  => $fineAmount,
                    'reason'  => "تأخیر {$daysOverdue} روز در بازگرداندن کتاب"
                ]);

                User::where('id', $reservation->user_id)
                    ->update([
                        'score' => DB::raw("GREATEST(score - ($daysOverdue * 5), 0)"), // حداقل امتیاز ۰
                        'penalty_count' => DB::raw("penalty_count + 1"),
                    ]);
            }
        });

        return true;
    }
}
