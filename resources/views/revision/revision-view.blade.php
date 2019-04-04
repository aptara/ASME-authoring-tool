@extends('layouts.app')

@section('content')
<div class="container page-partner-with-us">
 <div class="inner-container">
  <div class="action-bar">
    <div class="right-text">
      {{ Breadcrumbs::render('view-revision', $chapter, $revision) }}

      <h2>{{ $chapter->name }}</h2>
      <strong>Revision created By {{$revision->user->first_name}} {{$revision->user->last_name}} on {{\Carbon\Carbon::parse($revision->updated_at)->format('d/m/Y h:i:s')}}</strong>
    </div>
  </div>
  <div class="row">
    <div class="main-content col-md-12">
      <div class="contact-wrapper">
        <div class="contact-inner clearfix">
          <textarea id="revisionText" style="display: none;" name="chapterText" class="form-control" rows="10">{{$revision->approved_text}}</textarea>
          <div id="revisionViewText">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  window.onload = function()
  {
    var textValue = document.getElementById('revisionText').value;
    document.getElementById('revisionViewText').innerHTML = textValue;
  }
</script>
@endsection
