@extends('layouts.app')

@section('content')

<div class="container mt-2">
    <div class="row">
        <div class="col-md-6">
            <h3>
                Edit Branch
            </h3>
        </div>
        <div class="col-md-6" style="text-align:right;">

            <a class="btn btn-primary" href="{{ url('home') }}"> Back</a>
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
    <form action="{{ route('branches.update',$branch->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Branch ID:</strong>
                    <input type="text" name="branch_id" value="{{ $branch->branch_id }}" class="form-control" placeholder="branch ID">
                    @error('branch_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Branch Name:</strong>
                    <input type="text" name="name" value="{{ $branch->name }}" class="form-control" placeholder="branch name">
                    @error('name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Branch Unit No.:</strong>
                    <input type="text" name="unit_no" class="form-control" placeholder="Branch Unit No." value="{{ $branch->unit_no }}">
                    @error('unit_no')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Branch Lease Code:</strong>
                    <input type="text" name="lease_code" value="{{ $branch->lease_code }}" class="form-control" placeholder="branch Lease No.">
                    @error('lease_code')
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