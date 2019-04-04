<?php

namespace App\Modules\Invite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\InviteCreated;
use App\Modules\Invite\Invite;
use App\Temp_User;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Storage;

class InviteContributorController extends Controller {
    public function contributorList() {
        $users = User::all();
        return view('invite.contributor-list', compact('users'));
    }

    public function contributorActivate(int $userId) {
        User::where('id', '=', $userId)->update(['active_status' => 1]);
        return redirect()->back();
    }

    public function contributorDeactivate(int $userId) {
        User::where('id', '=', $userId)->update(['active_status' => 0]);
        return redirect()->back();
    }

    public function contributorDelete(int $userId) {
        $user = User::find($userId);
        $user->delete();
        return redirect()->back();
    }

    public function inviteContributor() {
        return view('invite.invite-contributor');
    }

    public function sendMail(Request $request) {
        $validator = Validator::make($request->all(), [
            'emailId' => 'required',
            'contributorName' => 'required',
            'emailMessage' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $emailId = $request->input('emailId');
        $contributorName = $request->input('contributorName');
        $emailMessage = $request->input('emailMessage');

        do {
            $token = str_random();
        } while (Invite::where('token', $token)->first());

        $inviteRow = Invite::where('email', '=', $emailId)->get();

        if (count($inviteRow)) {
            return redirect()
                ->back()
                ->with('error',
                    'Sorry invitation already send to this email id.')
                ->withInput();
        }
        else {
            $invite = Invite::create([
                'email' => $emailId,
                'name' => $contributorName,
                'message' => $emailMessage,
                'token' => $token
            ]);

            Mail::to($emailId)->send(new InviteCreated($invite));
            return redirect()
                ->back()
                ->with('success', 'Successful invite send.');
        }
    }

    public function acceptInvite($token) {
        if ($token === '59584e745a513d3d') {
            return view('invite.copyright-policy');
        }
        else {
            abort(404);
        }

    }

    public function contributorCopyright(Request $request) {
        $validator = Validator::make($request->all(), [
            'tempName' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $tempName = $request->input('tempName');
        $tempDate = $request->input('tempDate');
        $professionalAffiliation = $request->input('professional_affiliation');
        $asmeAffiliation = $request->input('asme_affiliation');

        $tempUser = Temp_User::create([
            'tempName' => $tempName,
            'tempDate' => $tempDate,
            'professional_affiliation' => $professionalAffiliation,
            'asme_affiliation' => $asmeAffiliation,
        ]);

        $userId = $tempUser->id;
        if ($tempUser) {
            return redirect()->route('register-form', $userId);
        }
    }

    public function registerUserForm($userId) {
        $tempUser = Temp_User::find($userId);
        if ($tempUser) {
            $tempName = $tempUser->tempName;
            return view('invite.register-user', compact('tempName', 'userId'));
        }
        else {
            abort(404);
        }
    }


    public function registerUser(Request $request) {
        $messages = [
            'password.regex' => 'Password must contain atleast 8 character with 1 alphabet and 1 number.',
            'contributorFirstName.regex' => 'First name must contains letters only.',
            'contributorLastName.regex' => 'Last name must contains letters only.',
        ];

        $validator = Validator::make($request->all(), [
            'contributorFirstName' => 'required|regex:/^[a-zA-Z]+$/',
            'contributorLastName' => 'required|regex:/^[a-zA-Z]+$/',
            'emailId' => 'required|email',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*?[A-Za-z])(?=.*?[0-9])/',
            'g-recaptcha-response' => 'required|captcha',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $firstName = $request->input('contributorFirstName');
        $lastName = $request->input('contributorLastName');
        $tempUserId = $request->input('userId');
        $email = $request->input('emailId');
        $password = bcrypt($request->input('password'));

        $userTempRow = Temp_User::find($tempUserId);

        $userRow = User::where('email', '=', $email)->get();
        if (count($userRow)) {
            return redirect()
                ->back()
                ->with('error',
                    'Sorry email id already registered with the system.')
                ->withInput();
        }
        $user = User::create([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'professional_affiliation' => $userTempRow->professional_affiliation,
            'asme_affiliation' => $userTempRow->asme_affiliation,
            'password' => $password,
            'active_status' => 1
        ])->assignRole('contributor');
        Temp_User::where('id', '=', $tempUserId)->delete();

        $userId = $user->id;
        $post = array('password' => $password, 'email' => $email);
        Auth::loginUsingId($userId);

        return redirect()->route('chapter-list')->with('success',
            'You are now allowed to contribute to the chapters.');;
    }
}
