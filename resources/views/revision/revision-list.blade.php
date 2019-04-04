@extends('layouts.app')

@section('content')
   <div class="container page-partner-with-us">
       <div class="inner-container">
          <div class="action-bar">
            <div class="right-text">
              {{ Breadcrumbs::render('revision-list', $chaptername[0]) }}
                <h2>Revisions for {{$chaptername[0]->name}}</h2>
            </div>
          </div>
          <div class="row">
          <div class="main-content col-md-12">
              <div class="contact-wrapper">
                <div class="contact-inner clearfix">
                     @if(count($revisions) == 0)
                        No revisions found
                     @else
                      <table class="table table-striped">
                        <tr>
                          <th>Edited by</th>
                          <th>Status</th>
                          <th>Updated at</th>
                          @if($user->hasRole('editor'))
                            <th>Changes submitted by contributor</th>
                          @endif
                          <th>Published version</th>
                        </tr>

                        @foreach ($revisions as $revision)
                            <tr>
                              <td>
                                  {{ $revision->contributor_name }}
                              </td>
                              <td>
                                @if($revision->status == 'Edited')
                                  Submitted
                                @else
                                  {{ $revision->status }}
                                @endif
                              </td>
                              <td>
                                {{ $revision->last_edited_date }}
                              </td>
                              @if($user->hasRole('editor'))
                              <td>
                                <a href="/chapters/compare/{{ $revision->cid }}/{{ $revision->id }}">View Changes</a>
                              </td>
                              @endif
                              @if($revision->status == 'Published')
                              <td>
                                <a href="/revisions/view/{{ $revision->id }}" target="_blank">View Published</a>
                              </td>
                              @else
                              <td></td>
                              @endif
                            </tr>
                        @endforeach
                      </table>
                    @endif
                </div>
              </div>
          </div>
       </div>
     </div>
    </div>

@endsection
