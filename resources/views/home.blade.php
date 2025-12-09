@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2 py-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>
                                Branches
                            </h3>
                        </div>
                        <div class="col-md-6" style="text-align:right;">
                            <a class="btn btn-success" href="{{ route('branches.create') }}"> Create Branch</a>
                        </div>


                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                            </div>
                            <div class="pull-right mb-2">
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Branch ID</th>
                                <th>Branch Name</th>
                                <th>Branch Unit No.</th>
                                <th>Branch Lease Code</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($branches->count() > 0)
                            @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->branch_id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->unit_no }}</td>
                                <td>{{ $branch->lease_code }}</td>
                                <td>
                                    <form action="{{ route('branches.destroy',$branch->id) }}" method="Post">
                                        <a class="btn btn-primary" href="{{ route('branches.edit',$branch->id) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="text-align: center;" colspan="6">
                                    <h4>
                                        No records found.
                                    </h4>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div style="width:100%;height:70px;margin-top:20px;text-align:right;" class="order-pagiate">

                        {!! $branches->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    table,
    th,
    td {
        border: 1px solid;
    }

    .btn {
        border: 2px solid black;
        border-color: #04AA6D;
        color: green;
        padding: 4px 8px;
        font-size: 16px;
        cursor: pointer;
        margin-bottom: 5px;

    }
</style>