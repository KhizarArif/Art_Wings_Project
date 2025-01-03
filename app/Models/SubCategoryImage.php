<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategoryImage extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'sub_category_images';

    public function subCategory():BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
}
