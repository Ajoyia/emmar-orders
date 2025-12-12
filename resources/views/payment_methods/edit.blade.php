@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="container mt-2">
                    <x-page-header 
                        title="Edit Payment Method" 
                        :backUrl="route('payment.methods')" 
                    />

                    <x-alert type="success" :message="session('status')" />

                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $payment->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Payment Method Name" 
                                    name="name" 
                                    :value="$payment->name" 
                                    placeholder="Payment Method Name" 
                                    required 
                                />
                            </div>
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Emaar Mapping" 
                                    name="emmar_mapping" 
                                    :value="$payment->emmar_mapping" 
                                    placeholder="Emaar Mapping" 
                                />
                            </div>
                            <div class="col-md-12">
                                <x-form-group 
                                    label="Share With Emaar" 
                                    name="share_with_emaar" 
                                    type="select"
                                    :options="[1 => 'Yes', 0 => 'No']"
                                    :selected="$payment->share_with_emaar"
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