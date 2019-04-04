@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Chapter's</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div  class="row">
                        <div class="col-sm">
                            a
                        </div>
                        <div class="col-sm">
                            a
                        </div>                        
                    </div>
                    <div  class="row">
                        <div class="col-sm">
                            a
                        </div>
                        <div class="col-sm">
                            a
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
