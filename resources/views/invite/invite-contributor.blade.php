@extends('layouts.app')

@section('content')
<div class="container">
    <div class="invite-form">
        <h2>Invite Contributor</h2>
        <form  method="POST" action="{{route('send-mail')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label for="emailId">Email Address:</label>
                <input type="email" name="emailId" placeholder="Enter email" class="form-control" id="emailId" value="{{old('emailId')}}" required>
                <div class="alert alert-danger" role="alert">{{$errors->first('emailId')}}</div>
            </div>
            <div class="form-group">
                <label for="contributorName">Contributor's Name:</label>
                <input type="text" name="contributorName" placeholder="Enter contributor name" class="form-control" id="contributorName" value="{{old('contributorName')}}" required>
                <div class="alert alert-danger" role="alert">{{$errors->first('contributorName')}}</div>
            </div>
            <div class="form-group">
                <label for="emailMessage">Email Message:</label>
                <textarea id="emailMessage" name="emailMessage" placeholder="Enter email message" class="form-control" rows="10" value="{{old('emailMessage')}}" required></textarea>
                <div class="alert alert-danger" role="alert">{{$errors->first('emailMessage')}}</div>
            </div>            
            <div class="form-group">
                <button type="submit" name="submitButton" value="SendInvite" class="btn btn-primary">Send Invite</button>
            </div>
        </form>
    </div>
</div>
@endsection
