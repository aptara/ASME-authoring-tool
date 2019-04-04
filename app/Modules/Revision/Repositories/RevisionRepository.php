<?php

namespace App\Modules\Revision\Repositories;

interface RevisionRepository
{
    public function getLatestRevision($chapter);

    public function createRevision($chapter, $data);

    public function updateRevision($data);

}
