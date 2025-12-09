@extends('layouts.app')

@section('content')


<!-- <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container">
                    <form action="{{ route('orders.fill') }}" method="Get">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Branch ID</label><br>
                                <input type="text" name="branch_id" class="form-control" />
                                @error('branch_id')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <button class="btn btn-success" type="submit">
                                    Send Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div> -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <h3>
                        Filters
                    </h3>
                </div>
            </div>
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container">
                    <form action="{{ route('orders') }}" method="Get">
                        <div class="row">

                            <div class="col-md-3">
                                <label>Branch Name</label><br>
                                <select name="branch_id" class="form-control">
                                    <option value='' selected>--Select--</option>
                                    @foreach($branches as $branch)

                                    <option value='{{$branch->branch_id}}'>{{$branch->name}}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label>Payment Method</label><br>
                                <select name="payment_method" class="form-control">
                                    <option value='' selected>--Select--</option>
                                    @foreach($payment_methods as $payment_method)

                                    <option value='{{$payment_method->payment_id}}'>{{$payment_method->name}}</option>
                                    @endforeach
                                </select>
                                @error('payment_method_name')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label>Business Date</label><br>
                                <input type="date" name="business_date" class="form-control" />
                                @error('business_date')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label>Sent To Emaar?</label><br>
                                <select name="send_to_emaar" class="form-control" id="send_to_emaar">
                                    <option value=1 selected>Yes</option>
                                    <option value=0>No</option>
                                </select>
                                @error('branch_id')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-12" style="text-align:right; margin-top:5px;">
                                <button class="btn btn-success" type="submit">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <!-- <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container">

                    <div class="row" style="align-items: flex-end;">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="order-input">
                                        <label for=""> Branch Name </label>
                                        <input type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="order-input">
                                        <label for=""> dfdsf </label>
                                        <input type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="order-input">
                                        <label for=""> dfdsf </label>
                                        <input type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="order-input">
                                        <label for=""> dfdsf </label>
                                        <input type="text" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="order-input">
                                <label></label>
                                <button class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <h3>
                        Orders
                    </h3>
                </div>
            </div>

            <div class="container mt-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <th>Payment Method</th>
                            <th>Branch Name</th>
                            <th>Price</th>

                            <th>Price Without Tax</th>
                            <th>Business Date</th>

                            <th>Sent To Emaar?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->count() > 0)
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>{{ $order->branch_name }}</td>
                            <td>{{ round($order->price,2) }} AED</td>
                            <td>{{ round($order->net_amount_without_tax,2) }} AED</td>
                            <td>{{ $order->business_date }}</td>
                            <td>{{ $order->is_sent_to_emaar==1 ? 'Yes' : 'No' }}</td>
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
                    {!! $orders->appends(request()->except('page'))->links() !!}
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

    .order-input {
        margin-bottom: 15px;
    }

    .order-input input,
    .order-input select {
        height: 50px;

    }

    .order-input button {
        width: 100%;
        margin: 8px 0 0;
        height: 50px;
    }
</style>