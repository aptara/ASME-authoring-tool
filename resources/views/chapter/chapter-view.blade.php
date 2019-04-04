@extends('layouts.app')

@section('content')
 <textarea id="chapterText" style="display: none;" name="chapterText" class="form-control" rows="10">{{$chapter->text}}</textarea>
 <div class="chapter-view">
   <div class="chapter-content">
     <div class="chapter-name">
      <h2><i>The Unwritten Laws of Engineering for the 21st Century</i></h2>

       @if($user->hasRole('editor'))
            <div class="button-group text-right">
              <a href="#"  class="book-text-edit">Edit</a>
              <a href="#"  class="book-text-close">Close</a>
            </div>
            <div class="book-text-form">
                <form  method="POST" action="{{route('chapter-intro-save')}}" novalidate>
                  {{csrf_field()}}
                      <textarea id="intro_text" name="intro_text" class="form-control" rows="8" required>{{$chapter->intro}}</textarea>
                      <input type="hidden" name="chapter_id" value="{{$chapter->id}}">
                      <div class="error">{{$errors->first('book_Text')}}</div>
                  <input type="submit" name="submitButton" value="submit" class="btn btn-primary book-text-submit" value="Submit">
                </form>
            </div>
        @endif
          <div class="book-text-p {{!$chapter->intro ? 'no-text': ''}}">
          <p>
            {!! $chapter->intro !!}
          </p>
        </div>

      <h2>{{$chapter->id}}. {{$chapter->name}}</h2>
     </div>
     <div class="chapter-text">
      <div id="chapterViewText">
      </div>
     </div>
   </div>
 </div>
 <script type="text/javascript">
  window.onload = function()
  {
    var textValue = document.getElementById('chapterText').value;
    document.getElementById('chapterViewText').innerHTML = textValue;

    //Replace &nbsp; with whitespace
    value = document.getElementById('chapterViewText').innerHTML;
    var count = (value.match(/&nbsp;/g) || []).length;
    for (var i = 0; i < count; i++)
    {
      value =  value.replace("&nbsp;", " ");
    }
    document.getElementById('chapterViewText').innerHTML = value;
  }
 </script>
@endsection
