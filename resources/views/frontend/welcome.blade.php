@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="hero_img_container">
            <h6 class="hero_title"> Art Wings By Sidra Munawar </h6>
            <img src="{{ asset('frontend_assets/img/heros.png') }}" alt="" class="hero_img">
        </div>
    </div>

    {{-- Introduction Start --}}
    @include('frontend.intro')
    {{-- Introduction End --}}

    {{-- New Arrivals Start  --}}
    <section class="m-4">
        @if ($newArrivalProducts->isNotEmpty())
            <h1 class="new_arrival_title"> New Arrivals</h1>
            <div class="new_arrival_container">

                <div class="row g-4" id="product-list">
                    @foreach ($newArrivalProducts as $newArrivalProduct)
                        <div
                            class="col-md-4 col-lg-3 col-sm-6 col-xs-12 my-2 filter-item all new d-flex flex-column justify-content-between">
                            <div class="card new_arrival_card border border-2">
                                <div class="img_container position-relative">
                                    <a href="javascript::void(0)">
                                        <img src="{{ asset('uploads/NewArrival/large/' . $newArrivalProduct->newArrivalImages->first()->image) }}"
                                            class="card-img-top shop-item-image" alt="">
                                    </a>
                                    <div class="overlay">
                                        <div class="icons">
                                            <a href="javascript::void(0)"
                                                onclick="newArrivalAddToCart('{{ $newArrivalProduct->id }}', '{{ $newArrivalProduct->newArrivalImages->first()->id }}', 'New Arrivals')">
                                                <i class="fa fa-shopping-cart" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top" title="view details"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <h3 class="card-title">{{ $newArrivalProduct->title }}</h3>
                                    <p class="card-text shop-item-price w-100 d-flex justify-content-center">
                                        <span class="original-price">Rs. {{ $newArrivalProduct->original_price }} </span>
                                        <span class="discounted-price">Rs. {{ $newArrivalProduct->price }} </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
    {{-- New Arrivals End --}}

    @include('frontend.categories')

    @include('frontend.featured')
    @include('frontend.imageGallary')
@endsection

@section('customJs')
    <script>
        document.getElementById('categories-dropdown').addEventListener('change', function() {
            const selectedCategoryId = this.value;
            filterCategories(selectedCategoryId);
        });


        $(document).ready(function() {
            $.ajax({
                url: "{{ route('front.getInitialCategory') }}",
                type: "GET",
                success: function(response) {
                    console.log("initial category", response.initialCategory);

                    if (response.status == true) {
                        $('#pills-tabContent').empty();

                        let subcategory = response.initialCategory[0];

                        let tabContent = `
                                <div class="tab-pane fade show active" id="pills-${subcategory.id}" role="tabpanel"
                                    aria-labelledby="pills-${subcategory.id}-tab">
                                    <div class="container-full mb-3">
                                        <div class="row">
                            `;

                        $.each(subcategory.sub_category_images, function(imgKey, subImage) {
                            tabContent += `
                                    <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12 my-2 filter-item all new d-flex flex-column justify-content-between">
                                        <div class="card border border-2">
                                            <div class="img_container position-relative">
                                                <a href="javascript:void(0)">
                                                    <img src="/uploads/subCategory/large/${subImage.image}"
                                                        class="card-img-top shop-item-image" alt="">
                                                </a>
                                                <div class="overlay">
                                                    <h5 class="shop-item-title">${subcategory.name}</h5>
                                                    <div class="icons">
                                                        <a href="/all_products/${subcategory?.slug}">
                                                            <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" 
                                                                data-placement="top" title="view details"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                    </div>
                                `;
                        });

                        tabContent += `
                                        </div>
                                    </div>
                                </div> 
                            `;

                        $('#pills-tabContent').append(tabContent);

                    } else {
                        console.log("Error", response.message);
                    }
                }

            });
        });


        function newArrivalAddToCart(productId, productImageId = null, feature = null) {
            $.ajax({
                url: "{{ route('front.addToCart') }}",
                type: "POST",
                data: {
                    id: productId,
                    image_id: productImageId,
                    feature: feature
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == true) {
                        window.location.href = "{{ route('front.cart') }}";
                    } else {
                        toastr.success(response.message);
                        console.log("Error", response.message);
                    }
                }
            })
        }

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

                // updateCart(rowId, newQty, $(this));
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

                // updateCart(rowId, newQty, $(this));
            }
        });

        function filterCategories(id) {
            $.ajax({
                url: "{{ route('front.filterCategories') }}",
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === true) {
                        $('#pills-tabContent').empty();
                        $.each(response.filterCategories, function(key, subcategory) {

                            let tabContent = `
                        <div class="tab-pane fade show active" id="pills-${subcategory.id}" role="tabpanel"
                            aria-labelledby="pills-${subcategory.id}-tab">
                            <div class="container-full mb-3">
                                <div class="row">
                    `;

                            $.each(subcategory.sub_category_images, function(imgKey, subImage) {
                                tabContent += `
                            <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12 filter-item all new d-flex flex-column justify-content-between">
                                <div class="card border border-2">
                                    <div class="img_container position-relative">
                                        <a href="javascript:void(0)">
                                            <img src="/uploads/subCategory/large/${subImage.image}"
                                                class="card-img-top shop-item-image" alt="">
                                        </a>
                                        <div class="overlay">
                                            <h5 class="shop-item-title">${subcategory.name}</h5>
                                            <div class="icons">
                                                <a href="/all_products/${subcategory?.slug}">
                                                    <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" 
                                                        data-placement="top" title="view details"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        `;
                            });

                            tabContent += `
                                </div>
                            </div>
                        </div> 
                    `;

                            $('#pills-tabContent').append(tabContent);
                        });

                    } else {
                        console.log("Error", response.message);
                    }
                }
            });
        }
    </script>
@endsection
