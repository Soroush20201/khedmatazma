<?php

namespace App\Repositories;

use App\Models\Edition;
use Illuminate\Support\Facades\Cache;

class EditionRepository
{
    public function getByBookId($bookId)
    {
        return Cache::remember("book_{$bookId}_editions", now()->addMinutes(60), function () use ($bookId) {
            return Edition::where('book_id', $bookId)->get();
        });
    }

    public function create(array $data)
    {
        return Edition::create($data);
    }

    public function update(Edition $edition, array $data)
    {
        $edition->update($data);
        return $edition;
    }

    public function delete(Edition $edition)
    {
        if ($edition->reservations()->count() > 0) {
            return false;
        }
        return $edition->delete();
    }
}
