<?php

namespace App\Helpers;

use App\Models\SubCategory;

function getSubCategories()
{
    return SubCategory::orderBy('id', 'desc')->where([['status', 'active'], ['showHome', 'yes']])->get();
}
