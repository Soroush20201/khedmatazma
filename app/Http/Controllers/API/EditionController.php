<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditionRequest;
use App\Http\Requests\UpdateEditionRequest;
use App\Http\Resources\EditionResource;
use App\Models\Edition;
use App\Repositories\EditionRepository;
use Illuminate\Http\Response;

class EditionController extends Controller
{
    protected $editionRepository;

    public function __construct(EditionRepository $editionRepository)
    {
        $this->editionRepository = $editionRepository;
    }

    public function index($book_id)
    {
        return ApiResponse::success(
            EditionResource::collection($this->editionRepository->getByBookId($book_id)),
            'Editions retrieved successfully'
        );
    }

    public function store(StoreEditionRequest $request)
    {
        $edition = $this->editionRepository->create($request->validated());
        return ApiResponse::success(new EditionResource($edition), 'Edition created successfully', Response::HTTP_CREATED);
    }

    public function show(Edition $edition)
    {
        return ApiResponse::success(new EditionResource($edition), 'Edition details retrieved successfully');
    }

    public function update(UpdateEditionRequest $request, Edition $edition)
    {
        $edition = $this->editionRepository->update($edition, $request->validated());
        return ApiResponse::success(new EditionResource($edition), 'Edition updated successfully');
    }

    public function destroy(Edition $edition)
    {
        if (!$this->editionRepository->delete($edition)) {
            return ApiResponse::error('Cannot delete edition with active reservations', Response::HTTP_CONFLICT);
        }

        return ApiResponse::success([], 'Edition deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
