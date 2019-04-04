<?php

namespace App\Modules\Lock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lock extends Model
{
    protected $guarded = [];
    protected $table = 'chapter_lock';

    /**
     * Get the revisions for the chapters.
     */
    public function chapter()
    {
        return $this->hasOne('App\Modules\Chapter\Chapter', 'cid', 'id');
    }

    /**
     * Get the user associated with the chapter.
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'uid');
    }

    public function checkExpiry($id)
    {
        return $this->whereDate('created_at', '>=', DB::raw('expires_on'))->where('id', $id)->first();
    }
}
