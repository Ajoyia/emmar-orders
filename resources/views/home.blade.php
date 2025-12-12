@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2 py-4">
                    <x-page-header 
                        title="Branches" 
                        :action="route('branches.create')" 
                        actionLabel="Create Branch" 
                    />

                    <x-alert type="success" :message="Session::get('success')" />

                    <x-data-table :headers="['S.No', 'Branch ID', 'Branch Name', 'Branch Unit No.', 'Branch Lease Code', 'Action']">
                        @if($branches->count() > 0)
                            @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->branch_id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->unit_no }}</td>
                                <td>{{ $branch->lease_code }}</td>
                                <td>
                                    <x-action-buttons 
                                        editRoute="branches.edit" 
                                        deleteRoute="branches.destroy" 
                                        :itemId="$branch->id" 
                                    />
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </x-data-table>

                    <x-pagination-wrapper :paginator="$branches" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection