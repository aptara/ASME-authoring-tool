<?php

namespace App\Modules\Revision\Repositories;

use App\Modules\Revision\Revision;

class EloquentRevision implements RevisionRepository
{
    public function getLatestRevision($chapter)
    {
        return $chapter->revisions()->orderBy('created_at', 'desc')->first();
    }

    public function createRevision($chapter, $data)
    {
        $latest_rev = $this->getLatestRevision($chapter);

        if ($latest_rev && $latest_rev->status != "Published" && $latest_rev->status != "Rejected") {
            $data['rid'] = $latest_rev->id;
            $this->updateRevision($data);
        } else {
            $data['cid'] = $chapter->id;
            $this->createNewRevision($data);
        }
    }

    public function updateRevision($data)
    {
        $revision = Revision::find($data['rid']);
        if (isset($data['text'])) {
            $revision->text = $data['text'];
        }
        $revision->status = $data['status'];
        $revision->approved_text = (isset($data['approved_text'])) ? $data['approved_text'] : "";
        return $revision->save();
    }

    private function createNewRevision($data)
    {
        $revision = new Revision();
        $revision->cid = $data['cid'];
        $revision->uid = $data['uid'];
        $revision->text = $data['text'];
        $revision->status = $data['status'];
        $revision->edited_status = 'created';
        $revision->approved_text = (isset($data['approved_text'])) ? $data['approved_text'] : "";
        return $revision->save();
    }

}
