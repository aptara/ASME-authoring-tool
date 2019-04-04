@extends('layouts.app')

@section('content')
<div class="container page-partner-with-us">
  <div class="inner-container">
    <div class="action-bar clearfix">
      {{ Breadcrumbs::render('edit-chapter', $chapter) }}
      <div class="right-text edit-chapter-name">
        <h2>Edit chapter {{$chapter->name}}</h2>
      </div>
      <div class="time-remaining">
        <p id="timeRemaining"></p>
      </div>
      <div class="autosave">
        <p id="autoSaveTime"></p>
      </div>
    </div>
    <div class="row tinymce-editor-div">
      <div class="main-content col-md-12">
        <div class="contact-wrapper">
          <div class="contact-inner clearfix">
            <div class="left-content"></div>
            <div class="right-content">
              <form  method="POST" action="{{route('save-chapter')}}">
                {{csrf_field()}}
                <div class="row">
                  <div class="col-lg-12">
                    <div class="">
                      <input id="expiredOn" name="expiredOn" type="hidden" value="{{ $expires_on }}">
                      <input id="uid" name="uid" type="hidden" value="{{ $user->id }}">
                      <input id="rid" name="rid" type="hidden" value="{{ $latest_rev->id }}">
                      <input id="uName" name="uName" type="hidden" value="{{ $user->first_name }}">
                      <input id="cid" name="cid" type="hidden" value="{{ $cid }}">
                      <textarea id="description_edit" name="description" class="form-control" rows="10" required>
                        {{$latest_rev->text}}
                      </textarea>
                      <div class="error">{{$errors->first('name')}}</div>
                    </div>
                  </div>
                </div>
                 <!--  <button type="submit" name="submitButton" value="draft" class="btn btn-primary">Save as draft</button>
                  <button type="submit" name="submitButton" value="submit" class="btn btn-primary">Submit</button> -->
                  <a href="#" data-value='draft' class="btn btn-primary edit-chapter-save">Save as draft</a>
                  <a href="#" data-value='submit' class="btn btn-primary edit-chapter-save">Submit</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="submitPopupModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p>Your changes will be submitted for review. Are you sure you want to proceed? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <div class="footer-btn">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-default btn-success" data-cid="0" id="submitPopupContinue"><span class="glyphicon glyphicon-off"></span> Continue</button>
        </div>
        <div class="footer-submit-text">
          Submitting please wait..!
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="unlockPopupModalAlert" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><!-- Chapter lock reverted --></h4>
      </div>
      <div class="modal-body">
        <p>Your access to edit this chapter have been removed. For more information please contact administrator.</p>
      </div>
      <div class="modal-footer">
        <a href="/chapters" class="btn btn-primary">OK</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="timeRemainingAlert" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><!-- Time remaining alert --></h4>
      </div>
      <div class="modal-body">
        <p>The lock will expire within next <span id='timeRemain'></span> mins please confirm your changes within time.</p>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="okClick()" id="timeRemainingContinue" data-flag='active' class="btn btn-primary" data-dismiss="modal">OK</button>

      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="timeExpireSave" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><!-- Chapter lock reverted --></h4>
      </div>
      <div class="modal-body">
        <p>The 24-hour editing period has expired. You will need to re-edit the chapter and submit within the 24-hour time period.<br>Thank you.</p>
      </div>
      <div class="modal-footer">
       <a href="/chapters" class="btn btn-primary">OK</a>
     </div>
   </div>
 </div>
</div>
<script src="{{asset('js/edit-page.js')}}"></script>
<script type="text/javascript">
  function okClick()
  {
    $('#timeRemainingContinue').attr('data-flag','deactive');
  }
</script>
@endsection
