@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2">
                    <x-page-header 
                        title="Create Branch" 
                        :backUrl="route('home')" 
                    />

                    <x-alert type="success" :message="session('status')" />

                    <form action="{{ route('branches.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Branch ID" 
                                    name="branch_id" 
                                    placeholder="Branch ID" 
                                    required 
                                />
                            </div>
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Branch Name" 
                                    name="name" 
                                    placeholder="Branch Name" 
                                    required 
                                />
                            </div>
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Branch Unit No." 
                                    name="unit_no" 
                                    placeholder="Branch Unit No." 
                                />
                            </div>
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Branch Lease Code" 
                                    name="lease_code" 
                                    placeholder="Branch Lease Code" 
                                />
                            </div>
                            <div class="col-md-12 text-right mt-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection