@extends('frontend.layouts.app')

@section('content')


<section class="container">
    <div class="row">
        <!-- Contact and Delivery Information -->
        <div class="col-md-12">
            <h1 class="text-center" style="color: #378CC6; padding-top: 20px"> Thank You For Your Order. Your Order Id is: {{ $order->id }} </h1>
            <h6 class="text-center" > Please Check Your Email For Confirmation. </h6>
            <div class="row w-100 py-3">
                <div class="col-md-12">
                    <div class="border p-4">
                        <h4 class="mb-4">Order details</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Contact information</h6>
                                <p>{{ $order->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Payment method</h6>
                                <p class="w-100">Cash on Delivery (COD) - Rs{{ number_format($order->grand_total, 2) }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Shipping address</h6>
                                <p class="w-100" > {{ $order->first_name }} {{ $order->last_name }} </p>
                                <p>{{ $order->address }}</p>
                                <p> {{ $order->country }} </p>
                                <p> {{ $order->phone }} </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Billing address</h6>
                                <p class="w-100" > {{ $order->first_name }} {{ $order->last_name }} </p>
                                <p>{{ $order->address }}</p>
                                <p> {{ $order->country }} </p>
                                <p> {{ $order->phone }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->

    </div>
</section>

@endsection

