<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'edition_id',
        'description',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}
