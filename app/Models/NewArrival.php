<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewArrival extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'new_arrivals';

    public function newArrivalImages():HasMany
    {
        return $this->hasMany(NewArrivalImage::class,'new_arrival_id', 'id');
    }
}
