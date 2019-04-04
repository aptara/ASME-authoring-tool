@extends('layouts.app')

@section('content')
<div class="container">
    <div class="invite-form">
        <h2>Upload XML file </h2>
        <form  method="POST" action="{{route('submit-xml')}}" enctype="multipart/form-data">
            {{csrf_field()}}

            <input type="file"  name="fileToUpload" id="fileToUpload">
		    <input type="submit" class="btn btn-primary" value="Upload File" name="submit">

        </form>
    </div>
</div>
@endsection
