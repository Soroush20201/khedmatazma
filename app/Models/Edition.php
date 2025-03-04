<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'condition', 'repair_count', 'available'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
