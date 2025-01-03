<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewArrivalImage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'new_arrival_images';

    public function newArrival(): BelongsTo
    {
        return $this->belongsTo(NewArrival::class, 'new_arrival_id', 'id');
    }
}
