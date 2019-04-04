<?php

namespace App\Modules\Invite;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $fillable = [
	    'email', 'name', 'message', 'token',
	];

	
}
