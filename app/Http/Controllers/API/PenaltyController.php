<?php
namespace App\Http\Controllers\API;

use App\Exceptions\PenaltyException;
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
        return PenaltyResource::collection($this->penaltyRepository->getAll());
    }

    public function checkAndApplyPenalties()
    {
        try {
            if (!$this->penaltyRepository->applyPenalties()) {
                throw new PenaltyException("No penalties were applied.");
            }

            return response()->json(['message' => 'Penalties applied successfully'], Response::HTTP_OK);
        } catch (PenaltyException $e) {
            return $e->render();
        } catch (\Exception $e) {
            Log::error('Penalty processing error: ' . $e->getMessage());

            return response()->json([
                'error'   => 'Penalty Processing Failed',
                'message' => 'مشکلی در پردازش جریمه‌ها رخ داده است.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


