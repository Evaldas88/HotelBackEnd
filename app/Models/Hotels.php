<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'price', 'image', 'travel_duration', 'country_id'
    ];
    public function countries()
    {
        return $this->belongsTo(Countries::class);
    }
    public function orders()
    {
        return $this->hasMany(Orders::class);
    }
}
