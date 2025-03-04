<?php

namespace App\Http\Controllers\API;

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
        return EditionResource::collection($this->editionRepository->getByBookId($book_id));
    }

    public function store(StoreEditionRequest $request)
    {
        $edition = $this->editionRepository->create($request->validated());
        return new EditionResource($edition);
    }

    public function show(Edition $edition)
    {
        return new EditionResource($edition);
    }

    public function update(UpdateEditionRequest $request, Edition $edition)
    {
        $edition = $this->editionRepository->update($edition, $request->validated());
        return new EditionResource($edition);
    }

    public function destroy(Edition $edition)
    {
        if (!$this->editionRepository->delete($edition)) {
            return response()->json(['message' => 'Cannot delete edition with active reservations'], Response::HTTP_CONFLICT);
        }

        return response()->json(['message' => 'Edition deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
