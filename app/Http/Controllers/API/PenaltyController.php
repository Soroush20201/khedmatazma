<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PenaltyResource;
use App\Jobs\ApplyPenaltiesJob;
use App\Repositories\PenaltyRepository;
use Illuminate\Http\Response;

class PenaltyController extends Controller
{
    protected $penaltyRepository;

    public function __construct(PenaltyRepository $penaltyRepository)
    {
        $this->penaltyRepository = $penaltyRepository;
    }

    public function index()
    {
        return PenaltyResource::collection($this->penaltyRepository->getAll());
    }

    public function checkAndApplyPenalties()
    {
        ApplyPenaltiesJob::dispatch();

        return response()->json(['message' => 'No penalties applied'], Response::HTTP_NO_CONTENT);
    }
}


