@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2">
                    <x-page-header 
                        title="Payment Methods" 
                        :action="route('payments.create')" 
                        actionLabel="Create Payment Method" 
                    />

                    <x-alert type="success" :message="Session::get('success')" />

                    <x-data-table :headers="['Sr. No', 'Payment ID', 'Name', 'Emaar Mapping', 'Share with Emaar', 'Action']">
                        @if($payment_methods->count() > 0)
                            @foreach ($payment_methods as $payment_method)
                            <tr>
                                <td>{{ $payment_method->id }}</td>
                                <td>{{ $payment_method->payment_id }}</td>
                                <td>{{ $payment_method->name }}</td>
                                <td>{{ $payment_method->emmar_mapping }}</td>
                                <td>{{ $payment_method->share_with_emaar == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <x-action-buttons 
                                        editRoute="payments.edit" 
                                        deleteRoute="payments.destroy" 
                                        :itemId="$payment_method->id" 
                                    />
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </x-data-table>

                    <x-pagination-wrapper :paginator="$payment_methods" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection