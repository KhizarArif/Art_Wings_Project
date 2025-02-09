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
                                        <a href="javascript:void(0)"
                                            onclick="newArrivalAddToCart('{{ $newArrivalProduct->id }}', '{{ $newArrivalProduct->newArrivalImages->first()->id }}', 'New Arrivals')">
                                            <i class="fa fa-shopping-cart" aria-hidden="true" data-toggle="tooltip"
                                                data-placement="top" title="Add TO Cart"></i>
                                        </a>

                                        <a href="javascript:void(0)"
                                            onclick="openImageModal([
                                            @foreach ($newArrivalProduct->newArrivalImages as $image)
                                                '{{ asset('uploads/NewArrival/large/' . $image->image) }}', @endforeach
                                        ])">
                                            <i class="fa fa-search-plus" aria-hidden="true" data-toggle="tooltip"
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