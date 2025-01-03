@if ($exhibitionProducts->isNotEmpty())
    <div class="container-fluid">
        <h1 class="new_arrival_title"> Exhibitions </h1>
        <div class="image_gallery">
            <div id="mz-gallery-container">
                <div id="mz-gallery">
                    @foreach ($exhibitionProducts as $exhibition)
                        @if (
                            $exhibition->exhibitionImages != null &&
                                $exhibition->exhibitionImages->isNotEmpty() &&
                                $exhibition->exhibitionImages->count() > 0)
                            @foreach ($exhibition->exhibitionImages as $image)
                                <figure>
                                    <img src="{{ asset('uploads/exhibition/large/' . $image->image) }}"
                                     alt="Statue of Liberty" width="700" height="700">
                                    <figcaption> {{ $exhibition->name }} </figcaption>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </figure>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
