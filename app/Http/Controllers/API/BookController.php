<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        return BookResource::collection($this->bookRepository->all());
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookRepository->create($request->validated());
        return new BookResource($book);
    }

    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = $this->bookRepository->update($book, $request->validated());
        return new BookResource($book);
    }

    public function destroy(Book $book)
    {
        $this->bookRepository->delete($book);
        return response()->json(['message' => 'Book deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}


