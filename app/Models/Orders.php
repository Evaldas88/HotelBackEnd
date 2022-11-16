<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'approved'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotels::class, 'hotel_id');
    }
}
