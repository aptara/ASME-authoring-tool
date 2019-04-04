<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp_User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'tempName', 'tempDate', 'professional_affiliation', 'asme_affiliation',
    ];
}
