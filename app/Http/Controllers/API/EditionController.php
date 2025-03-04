<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Edition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EditionController extends Controller
{

    public function index($book_id)
    {
        $editions = Edition::where('book_id', $book_id)->get();
        return response()->json($editions, Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'condition' => 'required|in:new,used,damaged',
            'repair_count' => 'nullable|integer|min:0',
            'available' => 'boolean',
        ]);

        $edition = Edition::create($request->all());

        return response()->json($edition, Response::HTTP_CREATED);
    }

    public function show(Edition $edition)
    {
        return response()->json($edition, Response::HTTP_OK);
    }

    public function update(Request $request, Edition $edition)
    {
        $request->validate([
            'condition' => 'sometimes|in:new,used,damaged',
            'repair_count' => 'nullable|integer|min:0',
            'available' => 'boolean',
        ]);

        $edition->update($request->all());

        return response()->json($edition, Response::HTTP_OK);
    }


    public function destroy(Edition $edition)
    {
        if ($edition->reservations()->count() > 0) {
            return response()->json(['message' => 'Cannot delete edition with active reservations'], Response::HTTP_CONFLICT);
        }

        $edition->delete();
        return response()->json(['message' => 'Edition deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
