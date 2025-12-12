@extends('layouts.app')

@section('content')
<div class="loader-container">
    <div class="loader"></div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="mb-4">APIs</h3>
                <div class="container mt-2">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>API Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="{{ route('orders.fill') }}" method="GET">
                                    <td>
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <span>Get Orders (Foodics)</span>
                                            </div>
                                            <div class="col-md-8">
                                                <select name="branch_id" class="form-control">
                                                    <option value="">--Select--</option>
                                                    @foreach($branches as $branch)
                                                    <option value="{{ $branch->branch_id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" type="submit">Execute</button>
                                    </td>
                                </form>
                            </tr>
                            <tr>
                                <td>Push Daily Orders (Emaar)</td>
                                <td>
                                    <a class="btn btn-success loader-trigger" href="{{ route('daily.orders') }}">Execute</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Push Monthly Orders (Emaar)</td>
                                <td>
                                    <a class="btn btn-success loader-trigger" href="{{ route('monthly.orders') }}">Execute</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="mb-4">Logs</h3>
                <div class="container mt-2">
                    <x-data-table :headers="['Sr No.', 'API Name', 'Status Code', 'Creation Date', 'Action']">
                        @if($logs->count() > 0)
                            @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->api_name }}</td>
                                <td>{{ $log->status_code }}</td>
                                <td>{{ $log->created_at }}</td>
                                <td>
                                    <x-action-buttons 
                                        viewRoute="logs.show" 
                                        :itemId="$log->id" 
                                    />
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </x-data-table>
                    <x-pagination-wrapper :paginator="$logs" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.loader-trigger').on('click', function() {
            $('.loader-container').css('display', 'flex');
        });
    });
</script>
@endpush