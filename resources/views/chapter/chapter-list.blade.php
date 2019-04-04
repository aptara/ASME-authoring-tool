@extends('layouts.app')

@section('content')
<div class="container page-partner-with-us">
 <div class="inner-container">
  <div class="action-bar">
    <div class="right-text">
      <div class="row">
        <div class="main-content col-md-12">
          <h3><b>The Unwritten Laws of Engineering for the 21st Century</b></h3>
          @if($user->hasRole('editor'))
          <div class="button-group text-right">
            <a href="#"  class="book-text-edit">Edit</a>
            <a href="#"  class="book-text-close">Close</a>
          </div>
          <div class="book-text-form">
              <form  method="POST" action="{{route('book-text-save')}}">
                {{csrf_field()}}
                    <textarea id="book_Text" name="book_Text" class="form-control" rows="8" required>{{$text->text}}</textarea>
                    <div class="error">{{$errors->first('book_Text')}}</div>
                <button type="submit" name="submitButton" value="submit" class="btn btn-primary book-text-submit">Submit</button>
              </form>
          </div>
          @endif
          <div class="book-text-p {{!$text->text ? 'no-text': ''}}">
            <p>
              {!! $text->text !!}
            </p>
          </div>
          </div>
        </div>
    </div>
  </div>
<!--   <div class="action-bar">
    <div class="right-text">
      <h3>List of Chapters</h3>
    </div>
  </div> -->
  <div class="row">
    <div class="main-content col-md-12">
      <div class="contact-wrapper">
        <div class="contact-inner clearfix">
          <table  class="table table-striped">
            <tr>
              <th>Chapter</th>
                      <!-- <th>Edited By</th>
                        <th>Last Updated At</th> -->
                        <!-- <th>Revisions</th> -->
                        @if($user->hasRole('contributor'))
                          <th>Last published at</th>
                        @else
                          <th>Status</th>
                        @endif
                        @if($user->hasRole('editor'))
                          <th>Last submitted at</th>
                        @endif

                        <th>Actions</th>

                        <!-- @if($user->hasRole('contributor'))
                          <th>Time Remaining</th>
                        @endif -->

                      </tr>
                      @foreach ($chapters as $chapter)
                      <tr>
                        <td>
                          <a href="/chapters/view/{{ $chapter->id }}" target="_blank"  class="">{{ $chapter->id }}. {{ $chapter->name }}</a><br>
                          
                          @if($user->hasRole('editor'))
                            @if($chapter->rCount > 0)
                              @if($chapter->rCount == 1)
                                <a href="/chapters/revisions/{{ $chapter->id }}"  class="">{{ $chapter->rCount }} revision</a>
                              @else
                                <a href="/chapters/revisions/{{ $chapter->id }}"  class="">{{ $chapter->rCount }} revisions</a>
                              @endif
                            @endif
                          @endif

                        </td>

                        @if($user->hasRole('contributor'))
                          <td>{{ $chapter->last_published_at }}</td>
                        @else
                          <td>
                            @if( $chapter->edited_status  == "created")
                              Being edited by {{ $chapter->edited_person }}
                            @elseif($chapter->edited_status ==  "user_submit")
                              Submitted for review by {{ $chapter->edited_person }}
                            @elseif($chapter->edited_status ==  "auto_submit")
                              Auto submitted by {{ $chapter->edited_person }}
                            @elseif($chapter->edited_status ==  "publish")
                              Published
                            @elseif($chapter->edited_status ==  "rejected")
                              Rejected
                            @endif
                            </td>
                        @endif

                        <!-- <td>
                            {{ $chapter->last_edited_by }}
                        </td> -->
                        @if($user->hasRole('editor'))
                        <td>
                             {{ $chapter->last_edited_date }}
                           </td>
                           @endif
<!--                         <td>
                           <a href="/chapters/revisions/{{ $chapter->id }}"  class="">Revisions</a>
                         </td> -->
                         <td>
                          @if($user->hasRole('contributor'))
                            @if($chapter->lock_status == "Available")
                              <a href="/chapters/edit/{{ $chapter->id }}"  class="btn btn-primary edit-chapter-btn" data-cid="{{$chapter->id}}" data-popup-show="{{($chapter->lock ? 'true' : 'false')}}">Edit</a>
                            @elseif($chapter->lock_status ==  "Draft")
                              <a href="/chapters/edit/{{ $chapter->id }}"  class="btn btn-primary edit-chapter-btn" data-cid="{{$chapter->id}}" data-popup-show="{{($chapter->lock ? 'true' : 'false')}}">Continue Editing</a>
                            @elseif($chapter->lock_status ==  "Locked")
                              Locked
                            @elseif($chapter->lock_status == "Pending")
                              Submitted
                            @endif

                            <span>
                            @if($chapter->lock_status =="Draft")
                              @if($chapter->time_days > 0)
                                  {{$chapter->time_days}} days left for submission
                                @elseif($chapter->time_hours >  0)
                                  {{ $chapter->time_hours}} hrs left for submission
                                @elseif($chapter->time_minutes > 0)
                                  {{$chapter->time_minutes}} mins left for submission
                              @endif
                            @endif
                          </span>
                          @endif


                          @if($user->hasRole('editor'))
                            @if($chapter->chapter_status == "Edited")
                             <a href="/chapters/compare/{{ $chapter->id }}/{{$chapter->rid}}" class="btn btn-primary">Review</a>
                            @elseif($chapter->lock_status ==  "Locked")
                              <a href="/chapters/unlock/{{$chapter->id}}" data-cid="{{$chapter->id}}" class="btn btn-primary unlock-chapter-btn">Unlock</a>
                            @else
                            @endif
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>


        <!-- Modal -->
        <div id="editPopupModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">This chapter will be locked</h4>
              </div>
              <div class="modal-body">
                <p> The chapter will be locked for 24 hours once you click continue. Are you sure you want to proceed? This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-default btn-success" data-cid="0" id="editLockedContinue"><span class="glyphicon glyphicon-off"></span> Continue</button>
              </div>
            </div>

          </div>
        </div>

        <!-- Modal -->
        <div id="unlockPopupModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">This chapter will be unlocked</h4>
              </div>
              <div class="modal-body">
                <p> The chapter will be unlocked and contributor is not able edit this chapter. Are you sure you want to proceed? This action cannot be undone.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <!-- <button type="submit" class="btn btn-default btn-success" data-cid="0" id="unlockContinue"><span class="glyphicon glyphicon-off"></span> Continue</button> -->
                <a href="" class="btn btn-default btn-success" data-cid="0" id="unlockContinue">Continue</a>
              </div>
            </div>

          </div>
        </div>

        @endsection
