<?php
namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookRepository
{
    public function all()
    {
        return Cache::remember('books', now()->addMinutes(60), function () {
            return Book::all();
        });
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data)
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book)
    {
        return $book->delete();
    }
}
