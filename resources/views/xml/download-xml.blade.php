@extends('layouts.app')

@section('content')
<div class="container">
	<div class="invite-form download-page">
	    <div class="header-container clearfix">
		<h2>Download Publish version</h2>
            <div class="download-all-wrapper">
                <div class="download-all-buttons">
                    <a href="{{ url("/") }}/download-all-xml"  class="btn btn-primary">Download all (XML)</a>
                    <a href="{{ url("/") }}/download-all-pdf"  class="btn btn-primary">Download all (PDF)</a>
                </div>
            </div>
        </div>
		<form  method="POST" action="{{route('submit-xml')}}">
			{{csrf_field()}}

			<div class="row">
				<div class="main-content col-md-12">
					<div class="contact-wrapper">
						<div class="contact-inner clearfix">
							<table  class="table table-striped">
								<tr>
									<th>Name</th>
									<th colspan='2'>Actions</th>
								</tr>
								@foreach ($chapters as $chapter)
								<tr>
									<td>
										<a href="{{ url("/") }}/chapters/view/{{ $chapter->id }}" target="_blank"  class="">{{$chapter->id}}. {{ $chapter->name }}</a>
									</td>
									<td>
										<a href="{{ url("/") }}/chapters/download/xml/{{ $chapter->id }}"  class="btn btn-primary"> Download XML</a>
									</td>
									<td>
										<a href="{{ url("/") }}/chapters/download/pdf/{{ $chapter->id }}"  class="btn btn-primary"> Download PDF</a>
									</td>
								</tr>
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
