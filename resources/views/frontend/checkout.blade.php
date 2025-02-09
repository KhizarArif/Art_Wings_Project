@extends('frontend.layouts.app')

@section('content')


    <section class="container mt-4">
        <div class="row ">
            <!-- Contact and Delivery Information -->
            <div class="col-md-6">
                <h5>Contact</h5>
                <form id="checkout_form">
                    @csrf
                    <input type="hidden" id="subtotal_input" name="subtotal_input"
                        value="{{ isset($subtotal) ? $subtotal : 0 }}">
                    <input type="hidden" id="shippingCharge_input" name="shippingCharge_input"
                        value="{{ isset($shippingCharge) ? $shippingCharge : 0 }}">
                    <input type="hidden" id="grandTotal_input" name="grandTotal_input"
                        value="{{ isset($grandTotal) ? $grandTotal : 0 }}">



                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Email or mobile phone number">
                    </div>
                    <h5>Delivery</h5>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-control" id="city" name="city" placeholder>
                            <option value=""> Select City... </option>
                            @if (!empty($allCities))
                                @foreach ($allCities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            @endif
                            <option value="250">Rest of Cities</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First name">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                    </div>


                    <h4>Shipping method</h4>
                    <div class=" shippingContainer">
                        <div>Standard Shipping </div>
                        <div class="shippingPrice" id="shippingPrice" name="shippingPrice"> Rs. 000.00 </div>
                    </div>

                    <h4>Payment</h4>
                    <div class=""> All transactions are secure and encrypted. </div>
                    <div class=" shippingContainer">
                        <div> Cash On Delivery (COD) </div>
                    </div>

                    <button type="submit" class="order_button">Complete order</button>

                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6 order_summary">

                <div class="border p-3 mb-3">
                    @if (!empty($checkoutContent))
                        @foreach ($checkoutContent as $content)
                            <div class="product_info">
                                <input type="hidden" class="form-control" name="qty" id="qty"
                                    value="{{ isset($content) ? $content->qty : 0 }}">

                                <div class="product_thumbnail_container">
                                    @if ($content->options->newArrivalImages && $content->options->newArrivalImages->image != null)
                                        <img class="product_thumbnail"
                                            src="{{ asset('uploads/NewArrival/small/' . $content->options->newArrivalImages->image) }}"
                                            alt="Product Thumbnail">
                                    @elseif ($content->options->productImage && $content->options->productImage->image != null)
                                        <img class="product_thumbnail"
                                            src="{{ asset('uploads/product/small/' . $content->options->productImage->image) }}"
                                            alt="Product Thumbnail">
                                    @endif

                                    <div class="product_qty_badge"> {{ $content->qty }} </div>
                                </div>
                                <div class="description ">
                                    <p class="product_name">{{ $content->name }}</p>
                                    <p class="product_price">Rs. {{ $content->price }}</p>
                                </div>

                            </div>
                        @endforeach
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p>Subtotal</p>
                        <p id="subtotal">Rs. {{ Cart::subtotal() }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Shipping</p>
                        <p id="shippingCharge">Rs. 000.00</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p>Total</p>
                        <p id="grandTotal">Rs. </p>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection


@section('customJs')
    <script>
        $("#checkout_form").submit(function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            $('input[name="qty"]').each(function() {
                formData.push({
                    name: 'qty',
                    value: $(this).val()
                });
            });

            $.ajax({
                url: "{{ route('front.processCheckout') }}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status == true) {
                        console.log("Successfull", response);
                        var encryptedOrderId = response.orderId;
                        console.log(encryptedOrderId);
                        window.location.href = "{{ route('front.thankyou', '') }}/" + encryptedOrderId;
                    }
                }
            })
        })


        $('#city').change(function() {
            var city_id = $(this).val();
            $.ajax({
                url: "{{ route('shipping.getShippingAmount') }}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    city_id: city_id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status == true) {
                        $('#shippingPrice').html('Rs ' + response.totalShippingCharges);
                        $('#shippingCharge').html('Rs ' + response.totalShippingCharges);
                        $('#grandTotal').html('Rs ' + response.grandTotal);
                        $("#grandTotal_input").val(response.grandTotal);
                        $("#shippingCharge_input").val(response.totalShippingCharges);
                        $("#subtotal_input").val(response.subTotal);

                    }
                }
            })
        });
    </script>
@endsection
