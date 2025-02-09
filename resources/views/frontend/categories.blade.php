<section>
    @foreach ($categories as $category)
        <div class="container">
            <h1 class="new_arrival_title">{{ $category->name }}</h1>
            <div class="new_arrival_container">
                <div class="row">
                    @foreach ($category->subCategories as $subCategory)
                        <div
                            class="col-md-4 col-lg-3 col-sm-6 col-xs-12 my-2 filter-item all new d-flex flex-column justify-content-between">
                            <div class="card new_arrival_card border border-2">
                                <div class="img_container position-relative">
                                    <a href="javascript:void(0)">
                                        @if ($subCategory->subCategoryImages->isNotEmpty())
                                            <img src="{{ asset('uploads/subCategory/large/' . $subCategory->subCategoryImages->first()->image) }}"
                                                class="card-img-top shop-item-image zoomable-image"
                                                alt="{{ $subCategory->name }}">
                                        @else
                                            <img src="{{ asset('path/to/default/image.jpg') }}"
                                                class="card-img-top shop-item-image zoomable-image"
                                                alt="{{ $subCategory->name }}">
                                        @endif

                                    </a>
                                    <div class="overlay">
                                        <h3 class="text-white text-center">{{ $subCategory->name }}</h3>
                                        <div class="icons">
                                            {{-- <a href="javascript:void(0)">
                                                <i class="fa fa-search-plus" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top" title="view details"></i>
                                            </a> --}}

                                            <a href="{{ route('frontend.subProducts', $subCategory->slug) }}">
                                                <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip"
                                                    data-placement="top" title="view details">
                                                </i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</section>
