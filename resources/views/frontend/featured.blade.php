<section>

    @if ($featuredProducts->count() > 0)
        <div class="container">
            <h1 class="new_arrival_title"> FEATURED PRODUCT</h1>
            @foreach ($featuredProducts as $item)
                <form action="" id="featuredProductForm">
                    @csrf
                    <div class="row">
                        <div class="product_container">
                            <!-- Product Image -->
                            <div class="featured_product_image col-md-6">
                                <img src="{{ asset('uploads/featured/' . $item->image) }}" alt="{{ $item->name }}">
                            </div>

                            <!-- Product Details -->
                            <div class="product_details col-md-6">
                                <h1 class="product_title">{{ $item->name }}</h1>
                                <p class="featured_price">Rs.{{ number_format($item->price) }}</p>
                                <p class="product-description">
                                    {{ $item->description }}
                                </p>

                                <div class="mt-4 d-flex align-items-end justify-content-between">
                                    <div class="input-group quantity" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-2 pb-2 sub rounded"
                                                {{-- data-id="{{ $content->rowId }}" --}}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control  border-0 text-center" name="qty"
                                            value="1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-2 pt-2 pb-2 add rounded"
                                                {{-- data-id="{{ $content->rowId }}" --}}>
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark btn-lg btn-bold border-1 rounded w-50 " type="submit"
                                        title="Add To Cart">Add To Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach



        </div>
    @endif
</section>
