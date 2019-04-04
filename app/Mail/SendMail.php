<?php

namespace App\Mail;

use App\Modules\Chapter\Chapter;
use App\Modules\Mail\Service\SESSendMail;
use App\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class SendMail extends Mailable
{
    public function notification_email($uid, $method, $cid)
    {
        $user = User::find($uid);
        $chapter = Chapter::find($cid);
        $name = $user->first_name;
        $email = $user->email;
        $chapterName = $chapter->name;
        $flashMessage = '';

        $editorUser = User::role('editor')->first();
        /*$editor_mail = $editorUser->email;
        $editor_name = $editorUser->name;
        */
        $editor_mail = env('FROM_EMAIL');
        $editor_name = env('FROM_NAME');

        switch ($method) {
            case 'Revision_accepted':
                $to = $email;
                $toName = $name;
                $from = $editor_mail;
                $fromName = $editor_name;
                $subject = 'Revision is accepted.';
                $message = 'Dear ' . $user->first_name . ' editor has accepted your contribution for the chapter "' . $chapterName . '".';
                $flashMessage = 'Changes have been published successfully.';
                break;

            case 'Revision_rejected':
                $to = $email;
                $toName = $name;
                $from = $editor_mail;
                $fromName = $editor_name;
                $subject = 'Revision is rejected.';
                $message = 'Dear ' . $user->first_name . ' editor has rejected your contribution for the chapter "' . $chapterName . '".';
                $flashMessage = 'Changes have been rejected successfully.';
                break;

            case 'Revision_created':
                $to = $editor_mail;
                $toName = $editor_name;
                $from = $user->email;
                $fromName = $user->first_name;
                $subject = 'Revision is created.';
                $message = 'Hello ' . $editor_name . ', ' . $user->first_name . ' has done some changes in the chapter "' . $chapterName . '" you can review the changes.';
                $flashMessage = 'Your changes are submitted for review';
                break;

            case 'Lock_expired':
                $to = $email;
                $toName = $name;
                $from = $editor_mail;
                $fromName = $editor_name;
                $subject = 'Lock Expired';
                $message = 'Dear ' . $name . ' lock is expired for the chapter "' . $chapterName . '" all changes made by you is saved.';
                break;
        }

        $data = array(
            'message' => $message,
            'subject' => $subject,
            'toName' => $toName,
        );

        /*Mail::send('mail.send-notification', ['data' => $data],
            function ($message) use ($to, $toName, $subject, $from, $fromName) {
                $message->to($to, $toName)->subject($subject);
                $message->from($from, $fromName);
            });*/
        $options = [
            'from' => $editor_mail,
            'fromName' => $editor_name,
            'to' => 'komal.savla@focalworks.in',
            'toName' => 'Komal',
            'subject' => $subject,
            'mailBody' => $message,
        ];

        Log::info('Showing user profile for user: ');
        $sendmail = new SESSendMail();
        $sendmail->mail($options);
        return redirect()->back()->with('success', $flashMessage);
    }
}
