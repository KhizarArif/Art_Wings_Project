@extends('frontend.layouts.app')

@section('content')

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header_container {
            text-align: center;
            margin: 20px 0;
        }

        .header_container h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .header_container nav {
            font-size: 0.9em;
            color: #888;
        }

        .header_container nav a {
            text-decoration: none;
            color: #000;
        }

        .loader {
            border: 4px solid #f3f3f3;
            /* Light grey */
            border-top: 4px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 2s linear infinite;
            margin: auto;
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

    <div>
        <header class="header_container">
            <h1>Shopping Cart</h1>
            <nav>
                <a href="#">Home</a> &gt;
                <a href="#">Your Shopping Cart</a>
            </nav>
        </header>
        @if ($contentCart != null && count($contentCart) > 0)
            <table class="cartContainer">
                <thead>
                    <tr class="tableCartHeader">
                        <th style="width: 45%;">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead> 
                <tbody>
                    @foreach ($contentCart as $content)
                        <tr class="product_body">
                            <td class="product_img_container">
                                @if ($content->options->newArrivalImages && $content->options->newArrivalImages->image != null)
                                    <img class="product_img"
                                        src="{{ asset('uploads/NewArrival/small/' . $content->options->newArrivalImages->image) }}"
                                        alt="">
                                @elseif ($content->options->productImage && $content->options->productImage->image != null)
                                    <img class="product_img"
                                        src="{{ asset('uploads/product/small/' . $content->options->productImage->image) }}"
                                        alt="">
                                @endif

                                <div class="product_information">
                                    <p class="product-name">{{ $content->name }}</p>
                                    <a href="javascript:void(0)" class="remove"
                                        onclick="deleteToCart('{{ $content->rowId }}')">Remove</a>
                                </div>
                            </td>
                            <td> Rs.{{ $content->price }} </td>
                            <td style="width: 20%;">
                                <div class="input_group quantity" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-2 pb-2 sub rounded"
                                            data-id="{{ $content->rowId }}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="w-50 border-0 text-center" name="qty"
                                        value="{{ $content->qty }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-2 pb-2 add rounded"
                                            data-id="{{ $content->rowId }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>

                            <td> <b> Rs.{{ $content->price * $content->qty }} </b> </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
            <div class="cart-summary">
                <div class="checkout_container">
                    <div class="subtotal">
                        <p>Subtotal</p>
                        <p>Rs. {{ Cart::subtotal() }}</p>
                    </div>
                    <p class="tax-shipping">Taxes and shipping calculated at checkout</p>
                    {{-- <button class="checkout-btn">CHECK OUT</button> --}}
                    <a href="{{ route('front.checkouts') }}"> <button class="checkout-btn"> CHECK OUT </button> </a>
                </div>
            </div>
        @endif
    </div>
    </div>


@endsection

@section('customJs')
    <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev();
            var qtyValue = parseInt(qtyElement.val());

            if ($('div').hasClass('alert-danger')) {
                return;
            }

            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();

                // Show loader and disable buttons
                $(".loader").show();
                $('.add').prop('disabled', true);

                updateCart(rowId, newQty, $(this));
            }
        });

        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());

            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();

                // Show loader and disable buttons
                $(".loader").show();
                $('.sub').prop('disabled', true);

                updateCart(rowId, newQty, $(this));
            }
        });


        function updateCart(rowId, qty, button) {
            console.log("rowId", rowId);
            $.ajax({
                url: "{{ route('front.updateCart') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    rowId: rowId,
                    qty: qty,
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status == false) {
                        toast.error(response.message);
                        $('.add, .sub').prop('disabled', false);
                    }
                    window.location.reload();
                },
                complete: function() {
                    // Hide loader and enable buttons
                    $('.loader').hide();
                    $('.add, .sub').prop('disabled', false);
                }
            })
        }

        function deleteToCart(rowId) {
            if (confirm("Are you sure You want to delete ?")) {
                $.ajax({
                    url: "{{ route('front.deleteToCart') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "delete",
                    data: {
                        rowId: rowId
                    },
                    dataType: 'json',
                    success: function(response) {
                        window.location.reload();
                    }
                })

            }
        }
    </script>
@endsection
