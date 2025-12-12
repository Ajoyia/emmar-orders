@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2">
                    <x-page-header 
                        title="Show Log For {{ $log->api_name }}" 
                        :backUrl="route('logs')" 
                    />

                    <x-alert type="success" :message="session('status')" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Creation Date</strong>
                                <input class="form-control" disabled value="{{ $log->created_at }}" />
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <strong>Request</strong>
                                <textarea class="form-control log-textarea" disabled>{{ $log->request }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <strong>Response</strong>
                                <textarea class="form-control log-textarea" disabled>{{ $log->response }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .log-textarea {
        width: 100%;
        height: 200px;
        font-size: 14px;
        font-family: monospace;
        resize: vertical;
    }
</style>
@endpush