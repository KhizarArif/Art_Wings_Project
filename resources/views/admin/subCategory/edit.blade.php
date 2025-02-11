@extends('admin.layouts.header')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4> Update Sub Category </h4>
                    <form class="custom-validation" novalidate id="subCategoryForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($subCategory->id) ? $subCategory->id : '' }}"
                            id="id">
                        <input type="hidden" name="category_id"
                            value="{{ isset($subCategory->category_id) ? $subCategory->category_id : '' }}" id="category_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ isset($subCategory->name) ? $subCategory->name : '' }}"
                                        placeholder="Category Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom02" class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                        value="{{ isset($subCategory->slug) ? $subCategory->slug : '' }}" placeholder="slug"
                                        readonly>

                                </div>
                            </div>
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

                        <div class="row" id="sub_category_gallery">
                            @if ($subCategoryImages->isNotEmpty())
                                @foreach ($subCategoryImages as $image)
                                    <div class="col-md-2" id="image-row-{{ $image->id }}">
                                        <div class="card">
                                            <input type="hidden" name="image_array[]" value="{{ $image->id }}">
                                            <img src="{{ asset('uploads/subCategory/small/' . $image->image) }}"
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Category </label>
                                    <select class="form-select" id="validationCustom03" name="category_id" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $subCategory->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Status </label>
                                    <select class="form-select" id="validationCustom03" name="status" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="active"
                                            {{ isset($subCategory->status) && $subCategory->status == 'active' ? 'selected' : '' }}>
                                            Active </option>
                                        <option value="inactive"
                                            {{ isset($subCategory->status) && $subCategory->status == 'inactive' ? 'selected' : '' }}>
                                            InActive </option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Show on Home </label>
                                    <select class="form-select" id="validationCustom03" name="show_on_home" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="yes"
                                            {{ isset($subCategory->showHome) && $subCategory->showHome == 'yes' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="no"
                                            {{ isset($subCategory->showHome) && $subCategory->showHome == 'no' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div>
                            <button class="btn btn-primary" type="submit" id="submit_btn">Submit</button>
                            <div id="submit_loader" style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end card -->
        </div>
    </div>
@endsection


@section('customJs')
    <script>
        $(document).ready(function() {

            Dropzone.options.image = {
                url: "{{ route('subcategories.updateImage') }}",
                maxFiles: 1,
                paramName: 'file',
                params: {
                    'product_id': '{{ $subCategory->id }}',
                    'slug': '{{ $subCategory->slug }}'
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

                    $("#sub_category_gallery").append(product);
                },
                complete: function(file) {
                    this.removeFile(file);
                }
            };


            // Delete Images
            function deleteImage(imageId) {
                $.ajax({
                    type: 'DELETE',
                    data: {
                        id: imageId
                    },
                    url: "{{ route('products.deleteImage') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#image-row-" + imageId).remove(); // Remove image from UI
                            console.log("Image deleted successfully");
                        } else {
                            console.error("Error deleting image: ", response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting image: ", error);
                    }
                });
            }



            $("#subCategoryForm").submit(function(e) {
                e.preventDefault();
                $("#submit_btn").attr("disabled", true).hide();
        $("#submit_loader").show();

                $.ajax({
                    url: "{{ route('subcategories.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        console.log('AJAX request successful', data);
                        if (data.status == true) {
                            window.location.href = "{{ route('subcategories.index') }}";
                            toastr.success(data.message);
                            console.log("Successfull", data);

                        } else {
                            toastr.error(data.error);
                            console.error('AJAX request failed', data);
                        }
                    },
                    error: function (xhr, status, error) {
                toastr.error("An error occurred. Please try again.");
                if (xhr.status === 422) {
                    var errors = $.parseJSON(xhr.responseText);
                    $.each(errors["errors"], function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }
                console.error("AJAX request failed", status, error);
            },
            complete: function () {
                // Re-enable the submit button and hide loader
                $("#submit_btn").attr("disabled", false).show();
                $("#submit_loader").hide();
            },
                });
            });



            $('#name').change(function() {
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

        });
    </script>
@endsection
