@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <h3>
                            API's
                        </h3>

                    </div>
                </div>
                <div class="container mt-2">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>API Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div style='display:none;' class="loader-container">
                                <div class="loader"></div>
                            </div>
                            <tr>
                                <form action="{{ route('orders.fill') }}" method="Get">
                                    <td>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <span>Get Orders (Foodics)</span>
                                            </div>

                                            <div class="col-md-8">

                                                <select name="branch_id" class="form-control">
                                                    <option value='' selected>--Select--</option>
                                                    @foreach($branches as $branch)
                                                    <option value='{{$branch->branch_id}}'>{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                <div class="alert alert-danger mt-1 mb-1" style=" padding: 5px 20px; display: inline-block;">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </td>
                                    <td><button class="btn btn-success" type="submit">Execute</button></td>
                                </form>
                            </tr>
                            <tr>
                                <td>Push Daily Orders (Emaar)</td>
                                <td><a class="btn btn-success" id="daily-orders" href="{{ route('daily.orders') }}">Execute</a></td>
                            </tr>
                            <tr>
                                <td>Push Monthly Orders (Emaar)</td>
                                <td><a class="btn btn-success" id="monthly-orders" href="{{ route('monthly.orders') }}">Execute</a></td>
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
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <h3>
                            Logs
                        </h3>

                    </div>
                </div>
                <div class="container mt-2">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>API Name</th>
                                <th>Status Code</th>
                                <th>Creation Date</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($logs->count() > 0)
                            @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->api_name }}</td>
                                <td>{{ $log->status_code }}</td>
                                <td>{{ $log->created_at }}</td>
                                <td> <a class="btn btn-success" href="{{ route('logs.show',$log->id) }}"><i class="fa-solid fa-eye"></i></a>
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
                        {!! $logs->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        $('#daily-orders').click(function(event) {
            $('.loader-container').css('display', 'block'); // set display property to "block"
        });
        $('#monthly-orders').click(function(event) {
            $('.loader-container').css('display', 'block'); // set display property to "block"

        });
        $('#daily-orders').click(function(event) {
            $('.loader-container').css('display', 'block'); // set display property to "block"

        });
    });
</script>
<style>
    table,
    th,
    td {
        border: 1px solid;
    }

    input[type=text] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        color: black;
        box-sizing: border-box;
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

    .loader-container {
        position: fixed;
        z-index: 99;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        align-items: center;
        /* From https://css.glass */
        background: rgba(255, 255, 255, 0.58);
        border-radius: 16px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(7.6px);
        -webkit-backdrop-filter: blur(7.6px);
        border: 1px solid rgba(255, 255, 255, 0.38);
    }

    .loader {
        z-index: 1000;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>