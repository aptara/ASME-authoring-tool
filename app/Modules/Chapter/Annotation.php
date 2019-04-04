<?php

namespace App\Modules\Chapter;

use Illuminate\Database\Eloquent\Model;

class Annotation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ranges' => 'array',
    ];

}
