@extends('frontend.layouts.app')

@section('content')

    <section class="m-5">
        @if ($products->isNotEmpty())
            <div class="new_arrival_container">

                <div class="row g-4" id="product-list">
                    @foreach ($products as $product)
                        <div
                            class="col-md-4 col-lg-3 col-sm-6 col-xs-12 filter-item all new d-flex flex-column justify-content-between">
                            <div class="card new_arrival_card border border-2">
                                <div class="img_container position-relative">
                                    <a href="javascript::void(0)">
                                        <img src="{{ asset('uploads/product/large/' . $product->productImages->first()->image) }}"
                                            class="card-img-top shop-item-image" alt="">
                                    </a>
                                    <div class="overlay">
                                        <div class="icons">
                                            <a href="javascript::void(0)"
                                                onclick="addToCart('{{ $product->id }}', '{{ $product->productImages->first()->id }}')">
                                                <i class="fa fa-shopping-cart" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top" title="Add To Cart"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <p class="card_title">{{ $product->title }}</p>
                                    <p class="w-100 d-flex justify-content-between align-items-center">
                                        <span class="discounted-price">Rs. {{ $product->price }} </span>
                                        <span class="original-price">Rs. {{ $product->original_price }} </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

@endsection
