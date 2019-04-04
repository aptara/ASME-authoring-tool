@extends('layouts.app')

@section('content')
   <div class="container">
       <div class="inner-container">
          <div class="main-content">
            <div>
              <h3>Revision Id : revision {{ $revisionChapter[0]->id }}</h3>
            </div>
            <form method="POST" action="{{route('ckSubmit-publish')}}">
              {{csrf_field()}}
              <div class="row">
                <input name="cid" type="hidden" value="{{ $publishChapter[0]->id }}">
                <input name="rid" type="hidden" value="{{ $revisionChapter[0]->id }}">
                <textarea id="updatedText" name="updatedText" class="form-control" rows="10">
                    {{ $revisionChapter[0]->text }}
                </textarea>
              </div>
              <button type="submit" class="btn btn-primary @if($revisionChapter[0]->status =='Published') disabled @endif" onclick="assignDataToForm()" @if($revisionChapter[0]->status =='Published') disabled="disabled"disabled @endif>SUBMIT</button>
            </form>
          </div>
       </div>
    </div> 
    <script type="text/javascript">
      function save() 
      {
        console.log(CKEDITOR.instances.updatedText.getData());
      }

      setTimeout(function()
      {
          CKEDITOR.replace( 'updatedText' );   
      },100);
    </script>
@endsection
