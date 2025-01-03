@extends('admin.layouts.header')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4> Add Category </h4>
                    <form class="custom-validation" novalidate id="categoryForm">
                        @csrf

                        <input type="hidden" name="id" id="id"
                            value="{{ isset($category->id) ? $category->id : '' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom01" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ isset($category->name) ? $category->name : '' }}"
                                        placeholder="Category Name" required>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="validationCustom02" class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                        value="{{ isset($category->slug) ? $category->slug : '' }}" placeholder="slug"
                                        readonly>
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Status </label>
                                    <select class="form-select" id="validationCustom03" name="status" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="active"
                                            {{ isset($category->status) && $category->status == 'active' ? 'selected' : '' }}>
                                            Active </option>
                                        <option value="inactive"
                                            {{ isset($category->status) && $category->status == 'inactive' ? 'selected' : '' }}>
                                            InActive </option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationCustom03" class="form-label"> Show on Home </label>
                                    <select class="form-select" id="validationCustom03" name="show_on_home" required>
                                        <option selected disabled value="">
                                            Select option ...
                                        </option>
                                        <option value="Yes"
                                            {{ isset($category->showOnHome) && $category->showOnHome == 'Yes' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="No"
                                            {{ isset($category->showOnHome) && $category->showOnHome == 'No' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                    <p></p>
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
            $("#categoryForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('categories.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        console.log('AJAX request successful', data);
                        if (data.status == true) {
                            window.location.href = "{{ route('categories.index') }}";
                            toastr.success(data.message);
                            console.log("Successfull", data);

                        } else {
                            toastr.error(data.error);
                            console.error('AJAX request failed', data);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred. Please try again.');
                        if (error.status === 422) {
                            var errors = $.parseJSON(error.responseText);
                            $.each(errors['errors'], function(key, val) {
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                        console.error('AJAX request failed', status, error);
                    }
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
