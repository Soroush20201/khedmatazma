<?php

namespace App\Jobs;

use App\Repositories\PenaltyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplyPenaltiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $penaltyRepository;

    public function __construct()
    {
        $this->penaltyRepository = new PenaltyRepository();
    }

    public function handle()
    {
        $this->penaltyRepository->applyPenalties();
    }
}

