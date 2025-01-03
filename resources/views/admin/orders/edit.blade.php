@extends('admin.layouts.header')
<style>
    @media print {
        .hide-on-print {
            display: none !important;
        }
    }
</style>

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Invoice</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Utility</a></li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="invoice-title"> 
                        <h3>
                            <img src="assets/images/logo-sm.png" alt="logo" height="24" />
                        </h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div>
                                <h4> {{ $order->first_name }} {{ $order->last_name }} </h4>
                                {{ $order->address }}<br>
                                {{ $order->cityName }} <br>
                                <div class="d-flex justify-content-between mt-2 w-50">
                                    <h6 class="fw-bold">Email:</h6>
                                    <p class="m-0">{{ $order->email }}</>
                                </div>
                                <div class="d-flex justify-content-between  w-50">
                                    <h6 class="m-0">Phone:</h5>
                                        <p class="m-0">{{ $order->phone }}</>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 ">
                            <div class="mb-2 d-flex align-item-center">
                                <h6 class="order_span">Order Id:</h6>
                                <p class="m-0">{{ $order->id }}</p>
                            </div>
                            <div class="mb-2 d-flex align-item-center">
                                <h6 class="order_span">Total:</h6>
                                <p class="m-0">Rs {{ number_format($order->grand_total) }}</p>
                            </div>
                            <div class="mb-2 d-flex align-item-center">
                                <h6 class="order_span"> Shipping Date:</h6>
                                <p class="m-0">{{ \Carbon\Carbon::parse($order->shipping_date)->format('d M, Y') }}</p>
                            </div>
                            <div class="mb-2 d-flex align-item-center">
                                <h6 class="order_span">Status:</h6>
                                @if ($order->status == 'pending')
                                    <p class="badge bg-danger text-center p-2">Pending</p>
                                @elseif ($order->status == 'shipped')
                                    <p class="badge bg-info">Shipped</p> 
                                @else
                                    <p class="badge bg-danger">Cancelled</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Order Status --}}
        <div class="col-md-4 hide-on-print">
            <div class="card">
                <div class="card-body">
                    <form  id="order_status_form">
                        @csrf 
                            <div class="mb-3 order_status_container">
                                <label for="status"> Order Status</label>
                                <select name="status" class="form-select" >
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                    </option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_date"> Shipping Date </label>
                                <div class="input-group"  id="datepicker2">
                                    <input type="text" name="shipping_date" class="form-control" placeholder="dd M, yyyy"
                                        data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                        data-provide="datepicker" data-date-autoclose="true">

                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary">Update</button>
                            </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div>
                <div class="p-2">
                    <h3 class="font-size-16"><strong>Order summary</strong></h3>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td><strong>Item </strong></td>
                                    <td class="text-center"><strong>Price</strong></td>
                                    <td class="text-center"><strong>Quantity</strong>
                                    </td>
                                    <td class="text-end"><strong>Totals</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($order->orderItems != null)
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td class="text-center">Rs. {{ number_format($item->price) }} </td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-end">Rs. {{ number_format($item->total) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center">
                                        <strong>Subtotal</strong>
                                    </td>
                                    <td class="thick-line text-end">Rs {{ number_format($order->subtotal) }}</td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center">
                                        <strong>Shipping</strong>
                                    </td>
                                    <td class="no-line text-end">Rs. {{ number_format($order->shipping) }}</td>
                                </tr>
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center">
                                        <strong>Total</strong>
                                    </td>
                                    <td class="no-line text-end">
                                        <h4 class="m-0">Rs. {{ number_format($order->grand_total) }}</h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-print-none mb-4">
                        <div class="float-end mb-4">
                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i
                                    class="fa fa-print"></i></a>
                            <a href="{{ route('orders.index') }}" class="btn btn-primary waves-effect waves-light ms-2">Back</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('customJs')
    <script>
         $("#order_status_form").on('submit', function(e) {
            if (confirm(" Are you sure you want to change status?")) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('orders.changeOrderStatus', $order->id) }}",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success(response.message);
                        window.location.href = "{{ route('orders.edit', $order->id) }}";
                    }
                })
            }
        })
    </script>
@endsection
