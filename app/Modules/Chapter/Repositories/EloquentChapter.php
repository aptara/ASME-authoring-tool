<?php

namespace App\Modules\Chapter\Repositories;

use App\Modules\Chapter\Chapter;

class EloquentChapter implements ChapterRepository
{
    public function getAllChapters()
    {
        return Chapter::with('revisions')->withCount([
            'revisions as latest_rev' => function ($query) {
                $query->select('id')->latest('id')->limit(1);
            }
        ])->orderBy('id', 'asc')->get();
    }

    public function updateChapter($data)
    {
        return Chapter::where('id', $data['cid'])->update(['text' => $data['approved_text']]);
    }

}
