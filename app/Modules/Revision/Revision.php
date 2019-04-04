<?php

namespace App\Modules\Revision;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $guarded = [];

    /**
     * Get the user associated with the revision.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'uid');
    }

}
