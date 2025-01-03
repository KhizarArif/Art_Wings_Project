@extends('admin.layouts.header')
@section('content')
    <h4 class="mb-4"> Update Shipping</h4>
    <form class="custom-validation" novalidate id="shippingForm">
        @csrf
        <input type="hidden" id="shipping_id" name="shipping_id" value="{{ $shippingCharge->id }}">
        <div class="card">
            <div class="card-body">
                <div class="row d-flex">
                    <div class="col-md-6">
                        <select name="city" id="city" class="form-control">
                            <option value=""> Select City... </option>
                            @if ($cities->isnotEmpty())
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ $shippingCharge->city_id == $city->id ? 'selected' : ''}} >{{ $city->name }}</option>
                                @endforeach
                                <option value="250"> Rest Of The Cities </option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ $shippingCharge->amount }}"   
                            placeholder="Shipping Amount">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary"> Save </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('customJs')
    <script>
        $('#shippingForm').on('submit', function(event) {
            event.preventDefault();
            var element = $(this);
            $.ajax({
                url: "{{ route('shipping.store') }}",
                type: "POST",
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    toastr.success(response.message); 
                    window.location.href = "{{ route('shipping.create') }}";
                },
                error: function(error) {
                    if (error.status === 422) {
                        toastr.error(error.errors);
                        var errors = $.parseJSON(error.responseText);
                        $.each(errors['errors'], function(key, val) {
                            $("#" + key + "_error").text(val[0]);
                        });
                    }
                }
            })
        });

        function deleteShipping(id) {
            var url = "{{ route('shipping.delete', 'ID') }}";
            var newUrl = url.replace('ID', id);
            Swal.fire({
                title: "Do you want to save the changes?",
                text: "Data will be lost if you don't save it",
                showDenyButton: true,
                confirmButtonText: "Delete",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        data: {},
                        url: newUrl,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire("Deleted Successfully!", "", "success");
                            window.location.href = "{{ route('shipping.create') }}";
                        }
                    });
                }
            });

        }
    </script>
@endsection
