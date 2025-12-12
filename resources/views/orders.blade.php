@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="mb-4">Filters</h3>
                <form action="{{ route('orders') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Branch Name</label>
                            <select name="branch_id" class="form-control">
                                <option value="">--Select--</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->branch_id }}" {{ request('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="">--Select--</option>
                                @foreach($payment_methods as $payment_method)
                                <option value="{{ $payment_method->payment_id }}" {{ request('payment_method') == $payment_method->payment_id ? 'selected' : '' }}>
                                    {{ $payment_method->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Business Date</label>
                            <input type="date" name="business_date" class="form-control" value="{{ request('business_date') }}" />
                            @error('business_date')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label>Sent To Emaar?</label>
                            <select name="send_to_emaar" class="form-control">
                                <option value="1" {{ request('send_to_emaar') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ request('send_to_emaar') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('send_to_emaar')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-success" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="mb-4">Orders</h3>
                <div class="container mt-2">
                    <x-data-table :headers="['Order No.', 'Payment Method', 'Branch Name', 'Price', 'Price Without Tax', 'Business Date', 'Sent To Emaar?']">
                        @if($orders->count() > 0)
                            @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>{{ $order->branch_name }}</td>
                                <td>{{ number_format($order->price, 2) }} AED</td>
                                <td>{{ number_format($order->net_amount_without_tax, 2) }} AED</td>
                                <td>{{ $order->business_date }}</td>
                                <td>{{ $order->is_sent_to_emaar == 1 ? 'Yes' : 'No' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </x-data-table>
                    <x-pagination-wrapper :paginator="$orders" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection