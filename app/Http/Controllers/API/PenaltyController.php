<?php
namespace App\Http\Controllers\API;

use App\Exceptions\PenaltyException;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\PenaltyResource;
use App\Jobs\ApplyPenaltiesJob;
use App\Repositories\PenaltyRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PenaltyController extends Controller
{
    protected $penaltyRepository;

    public function __construct(PenaltyRepository $penaltyRepository)
    {
        $this->penaltyRepository = $penaltyRepository;
    }

    public function index()
    {
        return ApiResponse::success(
            PenaltyResource::collection($this->penaltyRepository->getAll()),
            'Penalties retrieved successfully'
        );
    }

    public function checkAndApplyPenalties()
    {
        if (!$this->penaltyRepository->applyPenalties()) {
            return ApiResponse::error('No penalties were applied', Response::HTTP_NO_CONTENT);
        }

        return ApiResponse::success([], 'Penalties applied successfully');
    }
}


