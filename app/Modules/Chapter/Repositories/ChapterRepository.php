<?php

namespace App\Modules\Chapter\Repositories;

interface ChapterRepository
{
    public function getAllChapters();

    public function updateChapter($data);

}
