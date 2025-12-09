@extends('layouts.app')

@section('content')

<div class="container mt-2">
    <div class="row">
        <div class="col-md-6">
            <h3>
                Show Log For {{$log->api_name}}
            </h3>
        </div>
        <div class="col-md-6" style="text-align:right;">

            <a class="btn btn-primary" href="{{ url('logs') }}"> Back</a>
        </div>


        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            </div>
            <div class="pull-right mb-2">
            </div>
        </div>
    </div>
    @if(session('status'))
    <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
    </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <strong>Creation Date</strong>
                <input class="form-control" disabled value="{{$log->created_at}}"/> 
            </div>
        </div>
        <div class="col-md-12">
            <strong>Request</strong>
            <textarea disabled style="width: 100%; height: 200px; font-size: 16px; border: 1px solid #ccc;">
            {{$log->request}}
            </textarea>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>Response</strong>
            <textarea disabled style="width: 100%; height: 200px; font-size: 16px; border: 1px solid #ccc;">
            {{$log->response}}
            </textarea>
        </div>


    </div>
</div>
@endsection