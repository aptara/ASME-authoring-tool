<?php

namespace App\Modules\Lock\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Chapter\Chapter;
use App\Modules\Chapter\Repositories\ChapterRepository;
use App\Modules\Lock\Repositories\LockRepository;
use App\Modules\Revision\Repositories\RevisionRepository;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class LockChapterController extends Controller
{
    private $chapterRepo;
    private $revRepo;
    private $lockRepo;

    public function __construct(
        ChapterRepository $chapterRepository,
        RevisionRepository $revisionRepository,
        LockRepository $lockRepository
    ) {
        $this->chapterRepo = $chapterRepository;
        $this->revRepo = $revisionRepository;
        $this->lockRepo = $lockRepository;
    }

    public function lockChapter($cid)
    {
        $chapter = Chapter::find($cid);
        $user = Auth::user();
        $this->lockRepo->createLock($chapter, $user);

        $data = array(
            'text' => $chapter->text,
            'status' => 'draft',
            'uid' => $user->id
        );


        $this->revRepo->createRevision($chapter, $data);

        return new JsonResponse("Lock created sucessfully");
    }

    public function unlockChapter(int $cid)
    {
        $this->lockRepo->unlock($cid,true);

        return redirect()->route('chapter-list')->with('success', 'Chapter unlock successfully.');
    }
}
