@extends('layouts.app')
@section('content')
   <div class="container">
    <div class='publish-error-custom'>
       <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Please accept/reject the remaining changes before proceeding to Publish.</strong>
        </div>
    </div>
       <div class="inner-container">
          <div class="main-content">
            <div>
              {{ Breadcrumbs::render('chapters-compare', $publishChapter, $revisionChapter) }}

              <h2>{{ $publishChapter->name }}</h2>
              <strong>Revision submitted by {{$revisionChapter->user->first_name}} {{$revisionChapter->user->last_name}} on {{\Carbon\Carbon::parse($revisionChapter->updated_at)->format('d/m/Y h:i:s')}}</strong>
            </div>
            <div class="row tinymce-compare-div">
            <form method="POST" action="{{route('submit-changes')}}">
              {{csrf_field()}}
              <div class="mt-10">
                <input id="cid" name="cid" type="hidden" value="{{ $publishChapter->id }}">
                <input id="rid" name="rid" type="hidden" value="{{ $revisionChapter->id }}">
                <textarea id="updatedText" name="updatedText" class="form-control" rows="10">
                    {{ $revisionChapter->text }}
                </textarea>
              </div>
              @if($revisionChapter->status == "Edited")
                <div class="">
                  <!-- <button type="submit" name="submitButton" value="publish" class="btn btn-success btn-small">Publish</button>
                  <button type="submit" name="submitButton" value="reject" class="btn btn-primary btn-small">Reject</button> -->
                  <a href="#" data-value='publish' class="btn btn-success btn-small compare-chapter-save">Publish</a>
                  <a href="#" data-value='reject' class="btn btn-primary btn-small compare-chapter-save">Reject</a>
                </div>
              @endif

            </form>
          </div>
          </div>
       </div>
    </div>

    <!-- Modal -->
    <div id="comparePublishedModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to publish this chapter? This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <div class="footer-btn">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-default btn-success" data-cid="0" id="comparePublishedContinue"><span class="glyphicon glyphicon-off"></span> Continue</button>
            </div>
            <div class="footer-submit-text">
              Publishing please wait..!
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div id="compareRejectPopupModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to reject this chapter? This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
            <div class="footer-btn">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-default btn-success" data-cid="0" id="compareRejectContinue"><span class="glyphicon glyphicon-off"></span> Continue</button>
            </div>
            <div class="footer-submit-text">
              Rejecting please wait..!
            </div>
          </div>
        </div>
      </div>
    </div>



    <script src="{{asset('js/compare-page.js')}}"></script>
@endsection
