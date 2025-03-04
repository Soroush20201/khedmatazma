<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class PenaltyController extends Controller
{
    public function index()
    {
        return response()->json(Penalty::with('user')->get(), Response::HTTP_OK);
    }

    public function checkAndApplyPenalties()
    {
        $overdueReservations = Reservation::where('status', 'approved')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueReservations as $reservation) {
            $daysOverdue = Carbon::now()->diffInDays($reservation->due_date);
            $fineAmount = $daysOverdue * 1000; // هر روز تأخیر ۱۰۰۰ تومان جریمه

            Penalty::create([
                'user_id' => $reservation->user_id,
                'amount' => $fineAmount,
                'reason' => "تأخیر {$daysOverdue} روز در بازگرداندن کتاب"
            ]);

            $user = User::find($reservation->user_id);
            $user->decrement('score', $daysOverdue * 5); // کاهش ۵ امتیاز برای هر روز تأخیر
            $user->increment('penalty_count'); // افزایش تعداد جریمه‌ها
        }

        return response()->json(['message' => 'Penalties applied successfully'], Response::HTTP_OK);
    }
}

