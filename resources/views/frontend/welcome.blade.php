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
    @include('frontend.newArrivalProducts')
    {{-- New Arrivals End --}}

    @include('frontend.imageModal')

    @include('frontend.categories')

    @include('frontend.featured')

    @include('frontend.video')

    @include('frontend.imageGallary')
@endsection

@section('customJs')
    <script>
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
    </script>

    <script>
        function openImageModal(images) {
            console.log(images);

            // Get the carousel container
            const carouselImages = document.getElementById('carouselImages');
            carouselImages.innerHTML = ''; // Clear any previous content

            // Add images dynamically to the carousel
            images.forEach((imageUrl, index) => {
                const isActive = index === 0 ? 'active' : '';
                const slide = `
                <div class="carousel-item ${isActive}">
                    <img src="${imageUrl}" class="d-block w-100 img-fluid" alt="Slide ${index + 1}">
                </div>`;
                carouselImages.innerHTML += slide;
            });

            // Show the modal
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>

    {{-- Close Model --}}
    <script>
        // Close the modal programmatically
        document.querySelector('.btn-close').addEventListener('click', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
            modal.hide();
        });
    </script>
 
@endsection
