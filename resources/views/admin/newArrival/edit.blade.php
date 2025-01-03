@extends('admin.layouts.header')
@section('content')
    <h4> Update New Arrival Product </h4>
    <form class="custom-validation" novalidate id="new_arrivals_product_form">
        @csrf

        <input type="hidden" name="id" value="{{ isset($newArrivalProduct->id) ? $newArrivalProduct->id : null }}">

        <div class="row d-flex">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="validationCustom01" class="form-label"> Title </label>
                            <input type="text" class="form-control" name="title" id="title" placeholder=" Title "
                                value="{{ isset($newArrivalProduct->title) ? $newArrivalProduct->title : null }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom01" class="form-label"> Slug </label>
                            <input type="text" class="form-control" name="slug" id="slug" placeholder=" Slug "
                                value="{{ isset($newArrivalProduct->slug) ? $newArrivalProduct->slug : null }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom01" class="form-label"> Short Description. </label>
                            <input type="text" class="form-control" id="validationCustom01" name="short_description"
                                value="{{ isset($newArrivalProduct->short_description) ? $newArrivalProduct->short_description : null }}"
                                placeholder=" Short Description... " required>
                        </div>
                        <div class="mb-3">
                            <label for="validationCustom01" class="form-label"> Detail Description. </label>
                            <textarea class="form-control" placeholder=" Detail Description... " name="detail_description" id="validationCustom01"
                                rows="5" required>
                                {{ isset($newArrivalProduct->detail_description) ? $newArrivalProduct->detail_description : null }} 
                            </textarea>
                        </div>
                        <div class="row">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Media</h2>
                                    <div id="image" name="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="product_gallery">
                            @if ($newArrivalImages->isNotEmpty())
                                @foreach ($newArrivalImages as $image)
                                    <div class="col-md-2" id="image-row-{{ $image->id }}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                            <img src="{{ asset('uploads/NewArrival/small/' . $image->image) }}"
                                                class="card-img-top" alt="...">
                                            <div class="card-body">
                                                <a href="javascript:void(0)" onClick="deleteImage( '{{ $image->id }}')"
                                                    class="btn btn-danger btn-sm delete"> Delete </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label">Original Price </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" id="validationCustom01"
                                            value="{{ isset($newArrivalProduct->original_price) ? $newArrivalProduct->original_price : null }}"
                                            name="original_price" placeholder=" Original Price.... " required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label"> Price </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" id="validationCustom01"
                                            value="{{ isset($newArrivalProduct->price) ? $newArrivalProduct->price : null }}"
                                            name="price" placeholder=" Price.... " required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label"> Quantity </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" id="validationCustom01"
                                            value="{{ isset($newArrivalProduct->qty) ? $newArrivalProduct->qty : null }}"
                                            name="qty" placeholder="Quantity.... " required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Secong Column --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5> Product Status </h5>
                        <div class="mb-3">
                            <label for="validationCustom03" class="form-label"> Status </label>
                            <select class="form-select" id="validationCustom03" name="status" required>
                                <option selected disabled value="">
                                    Select option ...
                                </option>
                                <option value="active" {{ $newArrivalProduct->status == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ $newArrivalProduct->status == 'inactive' ? 'selected' : '' }}>
                                    InActive </option>
                            </select>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        <div>
            <button class="btn btn-primary m-3" type="submit">Submit</button>
        </div>
    </form>
    </div>
    </div>
    <!-- end card -->
    </div>
@endsection


@section('customJs')
    <script>
        Dropzone.options.image = {
            url: "{{ route('new_arrivals.updateImage') }}",
            maxFiles: 10,
            paramName: 'file',
            params: {
                'product_id': '{{ $newArrivalProduct->id }}',
                'slug': '{{ $newArrivalProduct->slug }}'
            },
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif, image/jpg, video/mp4, application/pdf",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                console.log("success", response);
                var fileType = file.type.split('/')[0];
                var product = '';

                if (fileType === 'image') {
                    product = `<div class="col-md-2 d-flex" id="image-row-${response.image_id}">
                <div class="card" >
                    <input type="hidden" name="image_array[]" value="${response.image_id}">
                    <img src="${response.ImagePath}" class="card-img-top" alt="...">
                    <div class="card-body"> 
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger btn-sm delete"> Delete </a>
                    </div> 
                </div>
            </div>`;
                }

                $("#product_gallery").append(product);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        };

        $('#title').change(function() {
            var element = $(this);
            $.ajax({
                type: "GET",
                url: "{{ route('getSlug') }}",
                data: {
                    title: element.val()
                },
                dataType: "json",
                success: function(response) {
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });

        function deleteImage(imageId) {
            $.ajax({
                type: 'DELETE',
                data: {
                    id: imageId
                },
                url: "{{ route('new_arrivals.deleteImage') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $("#image-row-" + imageId).remove(); // Remove image from UI
                        console.log("Image deleted successfully");
                        toastr.success(response.message);
                    } else {
                        toastr.success(response.message);
                        console.error("Error deleting image: ", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.success(error);
                    console.error("Error deleting image: ", error);
                }
            });
        }

        $("#new_arrivals_product_form").submit(function(e) {
            e.preventDefault();
            $("#submit_btn").attr("disabled", true).hide();
            $("#submit_loader").show();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{ route('new_arrivals.store') }}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    console.log('AJAX request successful', data);
                    if (data.status == true) {
                        toastr.success(data.message);
                        console.log("Successfull", data);
                        window.location.href = "{{ route('new_arrivals.index') }}";

                    } else {
                        toastr.error(data.error);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred. Please try again.");
                    if (xhr.status === 422) {
                        var errors = $.parseJSON(xhr.responseText);
                        $.each(errors["errors"], function(key, val) {
                            $("#" + key + "_error").text(val[0]);
                        });
                    }
                    console.error("AJAX request failed", status, error);
                },
                complete: function() {
                    // Re-enable the submit button and hide loader
                    $("#submit_btn").attr("disabled", false).show();
                    $("#submit_loader").hide();
                },
            });
        })
    </script>
@endsection
