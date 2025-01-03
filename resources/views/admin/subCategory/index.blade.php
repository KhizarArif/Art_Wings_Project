@extends('admin.layouts.header')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row d-flex align-items-center  ">
                        <div class="col-sm-6 col-md-6 col-lg-8">
                            <h4> Categories </h4>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-4 ">
                            <div class="button-items ">
                                <button type="button" class="btn btn-outline-info waves-effect waves-light "
                                    onclick="addCategoryPage()" style="float: right;"> Add Category </button>
                            </div>
                        </div>

                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>SR #</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th> Action </th>
                                </tr>
                            </thead>


                            <tbody>
                                @if ($subCategories != null)
                                    @foreach ($subCategories as $subCategory)
                                        <tr>
                                            <td>{{ $subCategory->id }}</td>
                                            <td>{{ $subCategory->name }}</td>
                                            <td>
                                                @if ($subCategory->status == 'active')
                                                    <svg class="text-success-500 h-4 w-4 text-success"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        aria-hidden="true" width="26" height="26">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="text-danger" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" aria-hidden="true" width="26"
                                                        height="26">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('subcategories.edit', $subCategory->id) }}"
                                                    class="btn btn-info" title="Edit"><i class="fas fa-user-edit"></i></a>
                                                <a href="javascript:void(0)" class="btn btn-danger" title="Delete"
                                                    onclick="deleteItem({{ $subCategory->id }})"><i
                                                        class="dripicons-document-delete"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('customJs')
        <script>
            function addCategoryPage() {
                window.location.href = "{{ route('subcategories.create') }}";
            }


            function deleteItem(subCategoryId) {
                var url = "{{ route('subcategories.delete', ':id') }}";
                url = url.replace(':id', subCategoryId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire("Deleted!", response.message, "success");
                                window.location.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire("Error!", "Failed to delete the Category ", "error");
                            }
                        });
                    }
                });
            }
        </script>
    @endsection
