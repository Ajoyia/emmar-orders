@extends('layouts.app')

@section('content')

<div class="container mt-2">
    <div class="row">
        <div class="col-md-6">
            <h3>
                Edit Payment Method
            </h3>
        </div>
        <div class="col-md-6" style="text-align:right;">

            <a class="btn btn-primary" href="{{ url('payment-methods') }}"> Back</a>
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
    <form action="{{ route('payments.update',$payment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <input type="hidden" name="id" value="{{ $payment->id }}">
                <div class="form-group">
                    <strong>Payment Method Name:</strong>
                    <input type="text" name="name" value="{{ $payment->name }}" class="form-control" placeholder="branch name">
                    @error('name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Emaar Mapping:</strong>
                    <input type="text" name="emmar_mapping" class="form-control" placeholder="Emaar Mapping" value="{{ $payment->emmar_mapping }}">
                    @error('emmar_mapping')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong> Share With Emaar:</strong>
                    <select name="share_with_emaar" class="form-control" id="share_with_emaar">
                        <option value=1 {{ $payment->share_with_emaar == 1 ? 'selected' : '' }}>Yes</option>
                        <option value=0 {{ $payment->share_with_emaar == 0 ? 'selected' : '' }}>No</option>
                    </select>
                    @error('share_with_emaar')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">

            </div>
            <div class="col-md-6" style="text-align:right;">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>
@endsection