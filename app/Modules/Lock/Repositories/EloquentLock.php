<?php

namespace App\Modules\Lock\Repositories;

use App\Mail\SendNotification;
use App\Modules\Chapter\Chapter;
use App\Modules\Lock\Lock;
use App\Modules\Revision\Repositories\RevisionRepository;
use App\Modules\Revision\Revision;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EloquentLock implements LockRepository {
    private $rev;

    public function __construct(RevisionRepository $revisionRepository) {
        $this->rev = $revisionRepository;
    }

    public function isChapterLocked($chapter) {
        if ($chapter->lock) {
            $chapterLockUid = $chapter->lock->uid;
            $user = Auth::user();
            if ($chapterLockUid == $user->id && $user->hasRole('contributor')) {
                return FALSE; /* false means it is available */
            }
            else {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function createLock($chapter, $user) {
        $lockRow = Lock::where('cid', '=', $chapter->id)->first();
        if (!$lockRow) {
            $lock = new Lock();
            $lock->cid = $chapter->id;
            $lock->uid = $user->id;
            $lock->expires_on = date('Y-m-d H:i:s', strtotime("+1 days"));
            return $lock->save();
        }
        return FALSE;
    }

    public function unlock($cid, $auto = FALSE) {
        $revisionRow = Revision::where('cid', '=', $cid)
            ->where('status', '=', 'draft')
            ->get();

        if (count($revisionRow)) {
            if ($auto) {
                Revision::where('cid', '=', $cid)
                    ->where('status', '=', 'draft')
                    ->update([
                        'status' => 'Edited',
                        'edited_status' => 'auto_submit'
                    ]);
            }
            else {
                Revision::where('cid', '=', $cid)
                    ->where('status', '=', 'draft')
                    ->update(['status' => 'Edited']);
            }
            return TRUE;
        }

        $editedRev = Revision::where('cid', '=', $cid)
            ->where('status', '=', 'Edited')
            ->get();
        if (count($editedRev) <= 0) {
            return Lock::where('cid', '=', $cid)->delete();
        }
    }

    public function lockExpired() {
//        $expiredLocks = Lock::where('expires_on', '<=', DB::raw('now()'))->get();
        $expiredLocks = Lock::all();

        if ($expiredLocks) {
            foreach ($expiredLocks as $exp) {
                $revisionRow = Revision::where('cid', '=', $exp->cid)
                    ->where('status', '=', 'draft')
                    ->first();

                if (strtotime($exp->expires_on) <= time() && $revisionRow) {

                    $cid = $exp->cid;
                    $uid = $exp->uid;
                    $this->unlock($cid, TRUE);

                    $editorUser = User::role('editor')->first();
                    $editor_mail = $editorUser->email;
                    $editor_name = $editorUser->first_name . ' ' . $editorUser->last_name;

                    $chapter = Chapter::where('id', '=', $cid)->first();
                    $user = User::where('id', '=', $uid)->first();

                    $userName = $user->first_name . ' ' . $user->last_name;

                    $mailData = [
                        'fromEmail' => $editor_mail,
                        'fromName' => $editor_name,
                        'message' => 'Dear Contributor, The 24-hour lock period for the chapter has expired. Another contributor may be editing the chapter. Please log in to re-edit the chapter and submit your changes within 24-hours',
                        'subject' => 'Chapter lock expired',
                    ];
                    Mail::to($user->email)
                        ->send(new SendNotification($mailData));

                    /*$mailClass = new SendMail();
                    $mailClass->notification_email($uid, 'Lock_expired', $cid);*/
                }
            }
        }
    }
}
