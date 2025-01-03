@extends('admin.layouts.header')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4> Add Exhibition </h4>
                    <form class="custom-validation" novalidate id="exhibitionForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Exhibition Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom02" class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                        placeholder="slug" readonly>

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

                        <div class="row" id="exhibition_gallery"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Status </label>
                                    <select class="form-select" id="validationCustom03" name="status" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="active"> Active </option>
                                        <option value="inactive"> InActive </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Show on Home </label>
                                    <select class="form-select" id="validationCustom03" name="showHome" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div>
                            <button class="btn btn-primary" type="submit">Submit</button>
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
            $("#exhibitionForm").submit(function(e) {
                e.preventDefault();
                $("#submit_btn").attr("disabled", true).hide();
                $("#submit_loader").show();

                $.ajax({
                    url: "{{ route('exhibitions.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        console.log('AJAX request successful', data);
                        if (data.status == true) {
                            window.location.href = "{{ route('exhibitions.index') }}";
                            toastr.success(data.message);
                            console.log("Successfull", data);

                        } else {
                            toastr.error(data.error);
                            console.error('AJAX request failed', data);
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

        // Dropzone has been added as a global variable.
        const dropzone = new Dropzone(".dropzone", {
            url: "{{ route('image.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif, image/jpg",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                console.log("success", response.image_id);
                console.log("success", response.ImagePath);
                var product = `
                            <div class="col-md-2" id="image-row-${response.image_id}" >
                                <div class="card" >
                                    <input type="hidden" name="image_array[]" value="${response.image_id}">
                                    <img src="{{ asset('${response.ImagePath}') }}" class="card-img-top" alt="...">
                                        <div class="card-body"> 
                                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger btn-sm delete"> Delete </a>
                                        </div> 
                                </div>
                            </div>
                `;

                $("#exhibition_gallery").append(product);
            },
            complete: function(file) {
                this.removeFile(file);
            }
        });

        function deleteImage(imageId) {
            $.ajax({
                url: "{{ route('delete.image', ['id' => ':imageId']) }}".replace(':imageId', imageId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $("#image-row-" + imageId).remove(); // Remove image from UI
                        console.log("Image deleted successfully");
                        toastr.success(response.message);
                    } else {
                        console.error("Error deleting image: ", response.message);
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting image: ", error);
                }
            });
        }
    </script>
@endsection
