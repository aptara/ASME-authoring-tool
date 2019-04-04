<?php

namespace App\Modules\Lock\Repositories;

interface LockRepository
{
    public function isChapterLocked($chapter);

    public function createLock($chapter, $user);

    public function unlock($cid);

    public function lockExpired();
}
