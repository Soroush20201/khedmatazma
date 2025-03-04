<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    // 📌 نمایش لیست کتاب‌ها
    public function index()
    {
        return response()->json(Book::all(), Response::HTTP_OK);
    }

    // 📌 ایجاد یک کتاب جدید
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books|max:13',
            'description' => 'nullable|string',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, Response::HTTP_CREATED);
    }

    // 📌 نمایش یک کتاب خاص
    public function show(Book $book)
    {
        return response()->json($book, Response::HTTP_OK);
    }

    // 📌 ویرایش یک کتاب
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:13|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
        ]);

        $book->update($request->all());

        return response()->json($book, Response::HTTP_OK);
    }

    // 📌 حذف یک کتاب
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}


