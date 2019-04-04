<?php

namespace App\Modules\Chapter\Http\Controllers;

use App\Book_Text;
use App\Http\Controllers\Controller;
use App\Mail\SendNotification;
use App\Modules\Chapter\Chapter;
use App\Modules\Chapter\Repositories\ChapterRepository;
use App\Modules\Lock\Lock;
use App\Modules\Lock\Repositories\LockRepository;
use App\Modules\Revision\Repositories\RevisionRepository;
use App\Modules\Revision\Revision;
use App\User;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Storage;
use Symfony\Component\HttpFoundation\Request;

class ChapterCrudController extends Controller
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

    public function viewChapter(Chapter $chapter)
    {
        $user = Auth::user();
        return view('chapter.chapter-view', compact('chapter', 'user'));
    }

    public function chapterList()
    {
        $all = $this->chapterRepo->getAllChapters();
        $user = Auth::user();
        $chapters = [];
        $timezone = env('APP_TIMEZONE_C');

        foreach ($all as $key => $chapter) {
            $cid = $chapter->id;
            $edited_status = '';
            $user_name = '';
            $chapters[$key]['name'] = $chapter->name;
            $chapters[$key]['id'] = $cid;
            $chapters[$key]['lock'] = $chapter->lock;
            $chapters[$key]['rCount'] = Revision::where('cid', '=', $cid)->where('status', '!=', 'draft')->count();
            $chapters[$key]['pCount'] = Revision::where('cid', '=', $cid)->where('status', '=', 'Published')->count();

            $status = $this->lockRepo->isChapterLocked($chapter);
            $chapters[$key]['lock_status'] = (!$status) ? "Available" : "Locked";
            $revision = $this->revRepo->getLatestRevision($chapter);
            $chapters[$key]['chapter_status'] = '';
            if ($revision) {
                if ($revision->uid == $user->id && $revision->status == "Edited") {
                    $chapters[$key]['lock_status'] = 'Pending';
                } else {
                    if ($revision->uid == $user->id && $revision->status == "draft") {
                        $chapters[$key]['lock_status'] = 'Draft';

                        $interval = $this->getTimeRemaining($cid);
                        $chapters[$key]['time_days'] = $interval->days;
                        $chapters[$key]['time_hours'] = $interval->h;
                        $chapters[$key]['time_minutes'] = $interval->i;
                        $chapters[$key]['time_seconds'] = $interval->s;
                    }
                }

                $chapters[$key]['chapter_status'] = $revision->status;
                $edited_status = $revision->edited_status;
                $revUserRow = User::find($revision->uid);
                $user_name = $revUserRow->first_name . ' ' . $revUserRow->last_name;
            }


            $chapters[$key]['edited_person'] = $user_name;
            $chapters[$key]['edited_status'] = $edited_status;
            $chapters[$key]['rid'] = '';
            $chapters[$key]['last_edited_by'] = '';
            $chapters[$key]['last_edited_date'] = '';

            $timestamp = $chapter->updated_at;
            $publishDate = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
            $publishDate->setTimezone($timezone);

//            $chapters[$key]['last_published_at'] = date('d-m-Y h:i:s', strtotime($publishDate));;
            $chapters[$key]['last_published_at'] = $publishDate->diffForHumans();


            if ($revision) {
                $timestamp = $revision->updated_at;
                $updatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
                $updatedDate->setTimezone($timezone);
                $chapters[$key]['last_edited_date'] = date('d-m-Y h:i:s', strtotime($updatedDate));

                $chapters[$key]['rid'] = $revision->id;
                $chapters[$key]['last_edited_by'] = isset($revision->user->first_name) ? $revision->user->first_name . ' ' . $revision->user->last_name : "";
                /* $chapters[$key]['last_edited_date'] = date('d-m-Y H:i:s', strtotime($revision->updated_at)); */
            }
            $chapters[$key] = (object)$chapters[$key];
        }

        $text = Book_Text::find(1);

        return view('chapter.chapter-list', compact('chapters', 'user', 'text'));
    }

    public function edit(Chapter $chapter)
    {
        $user = Auth::user();

        $revision = $this->revRepo->getLatestRevision($chapter);
        if ($revision->status == 'Edited' && $user->hasRole('contributor')) {
            return abort(403);
        }

        $lockExist = Lock::where('cid', '=', $chapter->id)->count();
        if ($lockExist <= 0) {
            return abort(403);
        }


        if ($status = $this->lockRepo->isChapterLocked($chapter)) {
            return redirect()->back()->with('error', 'Sorry this chapter is locked by another user.');
        } else {
            $cid = $chapter->id;
            $latest_rev = $this->revRepo->getLatestRevision($chapter);

            $expiresOn = Lock::select('expires_on')->where('cid', '=', $cid)->first();
            $expires_on = strtotime($expiresOn->expires_on) * 1000;

            if ($latest_rev && $latest_rev->uid == $user->id && $latest_rev->status == "draft") {
//                $chapter = $latest_rev;
            }

            if ($latest_rev->status === 'draft') {
                Revision::where('id', $latest_rev->id)->update(['edited_status' => 'created']);
            }

            return view('chapter.chapter-edit', compact('chapter', 'cid', 'latest_rev', 'user', 'expires_on'));
        }
    }


    public function saveChapter()
    {
        $text = Input::get('text');
        $status = Input::get('status');
        $cid = Input::get('cid');

        if (strlen($text) > 0 && strlen($status) > 0 && strlen($cid) > 0) {
            $user = Auth::user();
            $userName = $user->first_name . ' ' . $user->last_name;

            $uid = $user->id;
            $chapter = Chapter::find($cid);

            $editorUser = User::role('editor')->first();
            $editor_mail = $editorUser->email;
            $editor_name = $editorUser->first_name . ' ' . $editorUser->last_name;

            $revision = $this->revRepo->getLatestRevision($chapter);
            if ($revision->status == 'Edited' && $user->hasRole('contributor')) {
                return abort(403);
            }

            if ($status == 'submit') {
                $status = 'Edited';
                Revision::where('id', $revision->id)->update(['edited_status' => 'user_submit']);

                $mailData = [
                    'fromEmail' => $user->email,
                    'fromName' => $userName,
                    'message' => 'Hello ' . $editor_name . ', ' . $userName . ' has done some changes in the chapter ' . $chapter->name . ' you can review the changes.',
                    'subject' => 'Changes done in chapter',
                ];
                Mail::to($editor_mail)->send(new SendNotification($mailData));
            }


            $data = array(
                'text' => $text,
                'status' => $status,
                'uid' => $uid
            );

            $this->revRepo->createRevision($chapter, $data);


            if ($status == 'draft') {
                return redirect()->route('chapter-list')->with('success', 'Your changes have been saved as draft.');
            } else {
                return redirect()->route('chapter-list');
            }
        } else {
            return 'DataNotFound';
        }
    }

    /*     public function saveChapter(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $cid = $request->input('cid');
            $user = Auth::user();
            $uid = $user->id;
            $text = $request->input('description');
            $chapter = Chapter::find($cid);

            $editorUser = User::role('editor')->first();
            $editor_mail = $editorUser->email;
            $editor_name = $editorUser->name;

            $revision = $this->revRepo->getLatestRevision($chapter);
            if ($revision->status == 'Edited' && $user->hasRole('contributor')) {
                return abort(403);
            }


            $status = 'draft';
            if ($request->submitButton == 'submit')
            {
                $status = 'Edited';
                Revision::where('id', $revision->id)->update(['edited_status' => 'user_submit']);
                $userName = $user->first_name . $user->last_name;
                $mailData = [
                    'fromEmail' => $user->email,
                    'fromName' => $userName,
                    'message' => 'Hello ' . $editor_name . ', ' . $userName . ' has done some changes in the chapter ' . $chapter->name . ' you can review the changes.',
                    'subject' => 'Changes done in chapter',
                ];
                Mail::to($editor_mail)->send(new SendNotification($mailData));
            }


            $data = array(
                'text' => $text,
                'status' => $status,
                'uid' => $uid
            );

            $this->revRepo->createRevision($chapter, $data);

            if ($status == 'draft')
            {
                return redirect()->route('chapter-list')->with('success', 'Your changes have been saved as draft.');
            }
            else
            {
                return redirect()->route('chapter-list');
            }
        } */

    public function autoSaveDraft($cid)
    {
        $chapter = Chapter::find($cid);
        $status = Input::get('status');
        $text = Input::get('text');
        $rid = Input::get('rid');

        $revision = $this->revRepo->getLatestRevision($chapter);

        if ($revision->status == 'draft' && $revision->id == $rid) {
            $data = array(
                'rid' => $revision->id,
                'status' => $status,
                'text' => $text,
            );
            $this->revRepo->updateRevision($data);
        } else {
            return 'error';
        }
    }

    public function bookTextSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_Text' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $text = $request->input('book_Text');

        if (strlen($text) < 10) {
            return redirect()->route('chapter-list')->with('error', 'Text has atleast 10 characters long');
        }

        Book_Text::where('id', 1)->update(['text' => $text]);
        return redirect()->route('chapter-list')->with('success', 'Your changes have been saved');
    }

    public function chapterIntroSave(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chapter_id' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $text = $request->input('intro_text');
        $chapter_id = $request->input('chapter_id');
        /*
                if (strlen($text) < 10) {

                    return redirect()->route('view-chapter', $chapter_id)->with('error',
                        'Intro must contain atleast 10 characters');
                }*/

        Chapter::find($chapter_id)->update(['intro' => $text]);
        return redirect()->route('view-chapter', $chapter_id)->with('success', 'Your changes have been saved');
    }

    public function getTimeRemaining($cid)
    {
        $expiresOn = Lock::select('expires_on')->where('cid', '=', $cid)->first();
        $expiresOn = new DateTime($expiresOn['expires_on']);
        $currentTime = new DateTime();
        $interval = date_diff($currentTime, $expiresOn);
        return $interval;
    }
}
