<?php

namespace App\Modules\Revision\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Chapter\Chapter;
use App\Modules\Chapter\Repositories\ChapterRepository;
use App\Modules\Lock\Repositories\LockRepository;
use App\Modules\Revision\Repositories\RevisionRepository;
use App\Modules\Revision\Revision;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Storage;

class RevisionCrudController extends Controller {
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

    public function viewRevision(Revision $revision) {
        $chapter = Chapter::find($revision->cid);
        return view('revision.revision-view', compact('revision', 'chapter'));
    }

    public function revisionList($chapterid) {
        $user = Auth::user();
        $timezone = env('APP_TIMEZONE_C');

        $chaptername = Chapter::select('name')
            ->where('id', '=', $chapterid)
            ->get();
        $all = Revision::where('cid', '=', $chapterid);

        if ($user->hasRole("contributor")) {
            $all = $all->where('status', 'Published');
        }

        if ($user->hasRole("editor")) {
            $all = $all->where('status', '!=', 'draft');
        }
        $all = $all->get()->sortByDesc('id');
        $revisions = [];

        foreach ($all as $key => $revision) {

            $revisions[$key]['id'] = $revision->id;
            $revisions[$key]['cid'] = $revision->cid;
            /* $contributor_name = User::select('name')->where('id', '=', $revision->uid)->get(); */
            $contributor_name = User::find($revision->uid);

            $revisions[$key]['contributor_name'] = "unknown";
            if ($contributor_name->count() > 0) {
                $revisions[$key]['contributor_name'] = $contributor_name->first_name . ' ' . $contributor_name->last_name;
            }
            /*$revisions[$key]['approved_by'] = $revision->approved_by;*/
            $revisions[$key]['status'] = $revision->status;

            $timestamp = $revision->updated_at;
            $updatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp,
                'UTC');
            $updatedDate->setTimezone($timezone);


            $revisions[$key]['last_edited_date'] = date('d-m-Y h:i:s',
                strtotime($updatedDate));

            $revisions[$key] = (object) $revisions[$key];

        }

        return view('revision.revision-list',
            compact('revisions', 'chaptername', 'user'));
    }

    public function reviewChanges($chapterid, $revisionid) {
        $publishChapter = Chapter::find($chapterid);
        $revisionChapter = Revision::find($revisionid);
        return view('chapter.chapter-comparison')->with([
            'publishChapter' => $publishChapter,
            'revisionChapter' => $revisionChapter
        ]);
    }

    public function submitChanges() {
        $text = Input::get('text');
        $status = Input::get('status');
        $cid = Input::get('cid');
        $rid = Input::get('rid');

        if (strlen($text) > 0 && strlen($status) > 0 && strlen($cid) > 0 && strlen($rid) > 0) {
            $uid = Revision::select('uid')->where('id', '=', $rid)->get();

            $editorUser = User::role('editor')->first();
            $editor_mail = $editorUser->email;
            $editor_name = $editorUser->first_name . ' ' . $editorUser->last_name;

            $chapter = Chapter::find($cid);
            $user = User::find($uid)->first();
            $userName = $user->first_name . ' ' . $user->last_name;

            $data = [
                'cid' => $cid,
                'approved_text' => $text,
                'rid' => $rid
            ];

            switch ($status) {
                case 'publish':
                    if (strpos($text, '<delete') !== FALSE) {
                        return 'error';
                        /* return redirect()->back()->with('error', 'Please accept/reject the remaining changes before proceeding to Publish.
                        '); */
                    }
                    elseif (strpos($text, '<insert') !== FALSE) {
                        return 'error';
                        /* return redirect()->back()->with('error', 'Please accept/reject the remaining changes before proceeding to Publish.
                        '); */
                    }

                    Revision::where('id', $rid)
                        ->update(['edited_status' => 'publish']);

                    $data['status'] = 'Published';
                    $this->revRepo->updateRevision($data);
                    $this->chapterRepo->updateChapter($data);
                    $this->lockRepo->unlock($cid);


                    $mailData = [
                        'fromEmail' => $editor_mail,
                        'fromName' => $editor_name,
                        'message' => 'Dear ' . $userName . ' editor has accepted your contribution for the chapter ' . $chapter->name . '.',
                        'subject' => 'Changes has been accepted',
                    ];
                    //Mail::to($user->email)->send(new SendNotification($mailData));
                    break;

                case 'reject':
                    $data['status'] = 'Rejected';
                    $this->revRepo->updateRevision($data);
                    $this->lockRepo->unlock($cid);

                    Revision::where('id', $rid)
                        ->update(['edited_status' => 'rejected']);

                    $mailData = [
                        'fromEmail' => $editor_mail,
                        'fromName' => $editor_name,
                        'message' => 'Dear ' . $userName . ' editor has rejected your contribution for the chapter ' . $chapter->name . '.',
                        'subject' => 'Changes has been rejected',
                    ];
                    //Mail::to($user->email)->send(new SendNotification($mailData));
                    break;
            }
            $this->revRepo->updateRevision($data);
            return 'success';
        }
        else {
            return 'DataNotFound';
        }
    }


    /*     public function submitChanges(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'updatedText' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $cid = $request->input('cid');
            $text = $request->input('updatedText');
            $rid = $request->input('rid');
            $uid = Revision::select('uid')->where('id', '=', $rid)->get();

            $editorUser = User::role('editor')->first();
            $editor_mail = $editorUser->email;
            $editor_name = $editorUser->name;

            $chapter = Chapter::find($cid);
            $user = User::find($uid)->first();
            $userName = $user->first_name . $user->last_name;
            $data = [
                'cid' => $cid,
                'approved_text' => $text,
                'rid' => $rid
            ];



            switch ($request->submitButton)
            {
                case 'publish':
                    if (strpos($text, '<delete') !== false)
                    {
                        return redirect()->back()->with('error', 'Please accept/reject the remaining changes before proceeding to Publish.
                        ');
                    }
                    elseif (strpos($text, '<insert') !== false)
                    {
                        return redirect()->back()->with('error', 'Please accept/reject the remaining changes before proceeding to Publish.
                        ');
                    }

                    Revision::where('id', $rid)->update(['edited_status' => 'publish']);

                    $data['status'] = 'Published';
                    $this->revRepo->updateRevision($data);
                    $this->chapterRepo->updateChapter($data);
                    $this->lockRepo->unlock($cid);

                    $mailData = [
                        'fromEmail' => $editor_mail,
                        'fromName' => $editor_name,
                        'message' => 'Dear '.$userName.' editor has accepted your contribution for the chapter '.$chapter->name.'.',
                        'subject' => 'Changes has been accepted',
                    ];
                    Mail::to($user->email)->send(new SendNotification($mailData));
                    break;

                case 'reject':
                    $data['status'] = 'Rejected';
                    $this->revRepo->updateRevision($data);
                    $this->lockRepo->unlock($cid);

                    Revision::where('id', $rid)->update(['edited_status' => 'rejected']);

                    $mailData = [
                        'fromEmail' => $editor_mail,
                        'fromName' => $editor_name,
                        'message' => 'Dear '.$userName.' editor has rejected your contribution for the chapter '.$chapter->name.'.',
                        'subject' => 'Changes has been rejected',
                    ];
                    Mail::to($user->email)->send(new SendNotification($mailData));
                    break;
            }
            $this->revRepo->updateRevision($data);
            return redirect()->route('chapter-list');
        } */
}
