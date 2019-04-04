@extends('layouts.app')

@section('content')
  <div class="container page-contributor-list">
    <div class="inner-container">
      <div class="action-bar">
        <div class="right-text">
            <h3>List of Contributors</h3>
        </div>
        <!-- <div class="right-text">
            <a href="/invite-contributor" class="btn btn-primary" id="inviteContributorBtn">Invite New Contributor</a>
        </div> -->
      </div>
      <div class="row">
      <div class="main-content col-md-12">
        <div class="contact-wrapper">
          <div class="contact-inner clearfix">
            <table  class="table table-striped">
              <tr>
                <th>Name</th>
                <th>Professional Affiliation</th>
                <th>ASME Affiliation</th>
                <th>Email id</th>
                <th>Actions</th>
              </tr>
              @foreach ($users as $user)
                @if($user->hasRole('contributor'))
                  <tr>
                    <td>
                      {{ $user->first_name }} {{ $user->last_name }}
                    </td>
                    <td>{{ $user->professional_affiliation }}</td>
                    <td>{{ $user->asme_affiliation }}</td>

                    <td>
                      {{ $user->email }}
                    </td>
                    @if($user->active_status == 0)
                      <td>
                        <a href="/contributors/activate/{{$user->id}}" class="contributors-btn" data-contributor-fun="activate" data-uid="{{$user->id}}">Activate</a>
                      </td>
                    @else
                      <td>
                        <a href="/contributors/deactivate/{{$user->id}}" class="contributors-btn" data-contributor-fun="deactivate" data-uid="{{$user->id}}">Deactivate</a>
                      </td>
                    @endif
                    {{--<td>
                      <a href="/contributors/delete/{{$user->id}}" class="contributors-btn" data-contributor-fun="delete" data-uid="{{$user->id}}">Delete</a>
                    </td>--}}
                  </tr>
                @endif
              @endforeach
            </table>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>

  <!-- Modal for contributirs function-->
    <div id="contributorFunPopupModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">This user will be activated</h4>
          </div>
          <div class="modal-body">
            <p> The User will be activated and able to contribute to any chapter once you click continue. Are you sure you want to proceed?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-default btn-success" data-fun='' data-uid="0" id="contributorFunContinue"><span class="glyphicon glyphicon-off"></span> Continue</button>
          </div>
        </div>

      </div>
    </div>

@endsection
