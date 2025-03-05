<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
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
        return ApiResponse::success(
            BookResource::collection($this->bookRepository->all()),
            'Books retrieved successfully'
        );
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookRepository->create($request->validated());
        return ApiResponse::success(new BookResource($book), 'Book created successfully', Response::HTTP_CREATED);
    }

    public function show(Book $book)
    {
        return ApiResponse::success(new BookResource($book), 'Book details retrieved successfully');
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = $this->bookRepository->update($book, $request->validated());
        return ApiResponse::success(new BookResource($book), 'Book updated successfully');
    }

    public function destroy(Book $book)
    {
        $this->bookRepository->delete($book);
        return ApiResponse::success([], 'Book deleted successfully', Response::HTTP_NO_CONTENT);
    }
}


