<style>
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-250px * {{ ceil($totalImages / 2) }})); /* Adjust based on total images */
        }
    }
</style>


@if ($exhibitionProducts->isNotEmpty())
    <div class="container-fluid">
        <h1 class="new_arrival_title"> Exhibitions </h1>
        <div class="slider">
            <div class="slide-track">
                @foreach ($exhibitionProducts as $exhibition)
                    @if (
                        $exhibition->exhibitionImages != null &&
                            $exhibition->exhibitionImages->isNotEmpty() &&
                            $exhibition->exhibitionImages->count() > 0)
                        @foreach ($exhibition->exhibitionImages as $image)
                            <div class="slide">
                                <img src="{{ asset('uploads/exhibition/small/' . $image->image) }}" class="image_slider_image" loading="lazy"  />
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
