@extends('layouts.app')

@section('content') 
  <div class="container page-partner-with-us">
    <div class="inner-container">
      <div class="action-bar">
        <div class="right-text">
          <h2>Edit chapter {{$chapter->name}}</h2>
        </div>
      </div>
    <div class="main-content col-md-12">
      <div class="contact-wrapper">
        <div class="contact-inner clearfix">
          <div class="left-content"></div>
            <div class="right-content">
              <form  method="POST" action="{{route('submit-revision')}}">
                {{csrf_field()}}
                <div class="row">
                  <div class="col-lg-12">
                    <div class="">
                      <label for="name">Text</label>
                      <input name="cid" type="hidden" value="{{ $chapter->id }}">
                      <textarea id="description_edit" name="description" class="form-control" rows="10">{{$chapter->text}}</textarea>
                      <div class="error">{{$errors->first('name')}}</div>
                    </div>
                  </div>
                </div>
                <button type="submit" onclick="save()" class="btn btn-primary">SUBMIT</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
  function save() 
  {
    console.log(CKEDITOR);
/*    console.log(CKEDITOR.instances.description_edit.getData());
    console.log(CKEDITOR.instances.description_edit.getCleanData());*/
  }

  setTimeout(function()
  {
    var editor1 = CKEDITOR.replace( 'description_edit',{ removeButtons: 'lite-toggletracking,lite-acceptall,lite-rejectall,lite-acceptone,lite-rejectone,lite-previous,lite-next,changePreviousTrack,changeNextTrack, roleChange,lite-toggleshow'});    
  },100);

  

</script>
@endsection
