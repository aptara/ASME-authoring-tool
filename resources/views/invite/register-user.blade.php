@extends('layouts.app')

@section('content')
<div class="container">
    <div class="invite-form">
        <h2>Sign up</h2>
        <form  method="POST" action="{{route('register-user')}}">
            {{csrf_field()}}

            <input id="userId" name="userId" type="hidden" value="{{ $userId }}">
            
            <div class="form-group">
                <label for="contributorFirstName">First name<span class="required">*</span> :</label>
                <input type="text" name="contributorFirstName" placeholder="Enter name" class="form-control" id="contributorFirstName" value="{{old('contributorFirstName')}}" required>
                <div class="alert alert-danger" role="alert">{{$errors->first('contributorFirstName')}}</div>
            </div>

            <div class="form-group">
                <label for="contributorLastName">Last name<span class="required">*</span> :</label>
                <input type="text" name="contributorLastName" placeholder="Enter name" class="form-control" id="contributorLastName" value="{{old('contributorLastName')}}" required>
                <div class="alert alert-danger" role="alert">{{$errors->first('contributorLastName')}}</div>
            </div>

            <div class="form-group">
                <label for="emailId">Email<span class="required">*</span> :</label>
                <input type="email" name="emailId" placeholder="Enter email" class="form-control" id="emailId" value="{{old('emailId')}}" required>
                <div class="alert alert-danger" role="alert">{{$errors->first('emailId')}}</div>
            </div>

            <div class="form-group">
                <label for="password">Password<span class="required">*</span> :</label>
                <input type="password" name="password" placeholder="Enter password" class="form-control" id="password">
                <div class="alert alert-danger" role="alert" required>{{$errors->first('password')}}</div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password<span class="required">*</span> :</label>
                <input type="password" name="password_confirmation" placeholder="Re-enter password" class="form-control" id="password_confirmation" required>                
            </div>

            <!-- <div class="form-group">
                <input type="checkbox" name="agree" value="check" id="agree" required/> I have read and agree to the Terms and Conditions and Privacy Policy.
                <div class="alert alert-danger" role="alert">{{$errors->first('agree')}}</div>
            </div> -->   

            <div class="form-group">                
                <div class="g-recaptcha" data-sitekey="6LfUzYcUAAAAAPWbiRQLHPhy5LcrcXk74UDm-4b4"></div>
            </div>

            <div class="form-group">
                <button type="submit" name="submitButton" value="Register" class="btn btn-primary">Sign up</button>
            </div>
        </form>
    </div>
</div>
@endsection
