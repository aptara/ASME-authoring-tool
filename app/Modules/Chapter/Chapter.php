<?php

namespace App\Modules\Chapter;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $guarded = [];

    /**
     * Get the revisions for the chapters.
     */
    public function revisions()
    {
        return $this->hasMany('App\Modules\Revision\Revision', 'cid', 'id');
    }

    /**
     * Get the user associated with the chapter.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'uid');
    }

    /**
     * Get the post's image.
     */
    public function lock()
    {
        return $this->hasOne('App\Modules\Lock\Lock', 'cid');
    }

}
