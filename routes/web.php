<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth', 'role:super-admin|editor|contributor']], function () {

    $revisionController = "\App\Modules\Revision\Http\Controllers\RevisionCrudController";
    $chapterController = "\App\Modules\Chapter\Http\Controllers\ChapterCrudController";
    $lockController = "\App\Modules\Lock\Http\Controllers\LockChapterController";

    Route::get('/home', "{$chapterController}@chapterList")->name('home');

    Route::get('/', "{$chapterController}@chapterList")->name('chapter-list');
    Route::get('chapters', "{$chapterController}@chapterList")->name('chapter-list');
    Route::post('/chapter/save', "{$chapterController}@saveChapter")->name('save-chapter');
    Route::get('/chapters/edit/{chapter}', "{$chapterController}@edit")->name('edit-chapter');

    /* chapter view routes */
    Route::get('/chapters/view/{chapter}', "$chapterController@viewChapter")->name('view-chapter');

    Route::post('/chapter/lock/{chapterid}', "{$lockController}@lockChapter")->name('lock-chapter');
    Route::post('/chapter/autosave/{chapterid}', "{$chapterController}@autoSaveDraft")->name('auto-save-chapter');
});

Route::group(['middleware' => ['auth', 'role:super-admin|editor']], function () 
{
    $chapterController = "\App\Modules\Chapter\Http\Controllers\ChapterCrudController";
    $inviteController = "\App\Modules\Invite\Http\Controllers\InviteContributorController";
    $revisionController = "\App\Modules\Revision\Http\Controllers\RevisionCrudController";
    $lockController = "\App\Modules\Lock\Http\Controllers\LockChapterController";
    $xmlController = "\App\Modules\Xml\Http\Controllers\XmlController";

    Route::get('/revisions/view/{revision}', "$revisionController@viewRevision")->name('view-revision');
    Route::get('/chapters/revisions/{chapterid}', "{$revisionController}@revisionList")->name('revision-list');
    
    Route::post('/booktextsave', "{$chapterController}@bookTextSave")->name('book-text-save');
    Route::post('/chapter-intro-save', "{$chapterController}@chapterIntroSave")->name('chapter-intro-save');


    Route::get('/chapters/compare/{chapterid}/{revisionid}',
        "{$revisionController}@reviewChanges")->name('review-changes');
    Route::post('/chapters/compare/save', "{$revisionController}@submitChanges")->name('submit-changes');

    //Invite contributor form
    Route::get('/invite-contributor', "{$inviteController}@inviteContributor")->name('invite-contributor');
    Route::post('/invite-send', "{$inviteController}@sendMail")->name('send-mail');

    Route::get('/contributors', "{$inviteController}@contributorList")->name('contributor-list');
    Route::get('/contributors/activate/{contributor}',
        "{$inviteController}@contributorActivate")->name('contributor-activate');
    Route::get('/contributors/deactivate/{contributor}',
        "{$inviteController}@contributorDeactivate")->name('contributor-deactivate');
    Route::get('/contributors/delete/{contributor}',
        "{$inviteController}@contributorDelete")->name('contributor-delete');

    /*Unlock chapter*/
    Route::post('/chapters/unlock/{chapterId}',
        "{$lockController}@unlockChapter")->name('unlock-chapter');

    /*XMl routes for uploding*/
    Route::get('/upload-xml', "{$xmlController}@uploadXML")->name('upload-xml');
    Route::post('/submit-xml', "{$xmlController}@submitXML")->name('submit-xml');

    Route::get('/download-xml', "{$xmlController}@downloadXML")->name('download-xml');
    Route::get('/download-all-xml', "{$xmlController}@downloadAllXML")->name('download-all-xml');
    Route::get('/download-all-pdf', "{$xmlController}@downloadAllPdf")->name('download-all-pdf');
    Route::get('/chapters/download/xml/{chapterId}', "{$xmlController}@downloadXMLFile")->name('download-xml-file');
    Route::get('/chapters/download/pdf/{chapterId}', "{$xmlController}@downloadPDFFile")->name('download-pdf-file');
});

Route::group(['middleware' => ['guest']], function () {
    $inviteController = "\App\Modules\Invite\Http\Controllers\InviteContributorController";
    Route::get('/invite-accept/{token}', "{$inviteController}@acceptInvite")->name('accept-invite');

    Route::get('/contributor-copyright', function () {
        abort(404);
    });
    Route::post('/contributor-copyright', "{$inviteController}@contributorCopyright")->name('contributor-copyright');
    Route::get('/register-form/{userId}', "{$inviteController}@registerUserForm")->name('register-form');
    Route::post('/register-user', "{$inviteController}@registerUser")->name('register-user');
});

$annotatorController = "\App\Modules\Chapter\Http\Controllers\AnnotatorController";
Route::get('/test', "{$annotatorController}@view");
Route::get('/annotation/test/search', "{$annotatorController}@search");
Route::post('/annotation/test/store', "{$annotatorController}@store")->name('store-annotator');
Route::post('/annotation/test/update/{id}', "{$annotatorController}@update");
