@extends('layouts.farmkonnect') @section('content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }
</style>
@endpush
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Home</li>\
                    <li class="active">Wallet</li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" id="summary">
            <div class="col-lg-1"></div>
            <div class="col-lg-10 text-center">
                <div class="card text-white bg-success mb-6">
                    <h3 class="font-weight-bold text-center mt-3">Request Delivery</h3>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Product</label>
                            <p class="font-weight-bold">{{ $order->product->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <label>Quantity</label>
                            <p class="font-weight-bold">{{ $order->quantity_ordered." ".$order->unit->unit_name.'(s)' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label>Vendor</label>
                            <p class="font-weight-bold">{{ $order->product->owner->$role->contact_person }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label>Pickup Location</label>
                            <p class="font-weight-bold" id="pickup_location">
                                {{ $order->product->owner->$role->location }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label>Logistics Company</label>
                                <select id="logistic_company" class="form-control">
                                    <option></option>
                                    @foreach($logistics as $company)
                                    <option value="{{ $company->uuid }}" rate="{{ $company->rate }}">
                                        {{ $company->name }} -
                                        &#8358;{{ number_format($company->rate, 2)." per KM" }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Delivery Address</label>
                                <input type="text" class="form-control" id="delivery-address"
                                    aria-describedby="emailHelp" readonly placeholder="Select Address On Google Maps">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Distance</label>
                                        <p id="distance" class="font-weight-bold"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Delivery Fee (&#8358;)</label>
                                        <p id="fee" class="font-weight-bold"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Expected Date of Delivery</label>
                                <input class="form-control" id="date" type="date" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label>Other Details</label>
                                <textarea id="details" rows="5" class="form-control"></textarea>
                            </div>
                            <button type="button" id="request_delivery" class="btn btn-light">
                                Request Delivery</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript" src="{{ asset('js/place-picker.js') }}"></script>
<script>
    $('document').ready(function() {
        $('select').select2({
            placeholder: 'Select an option'
        });
        $('#delivery-address').PlacePicker({
                key:"{{ env('GOOGLE_MAPS_API_KEY') }}",
                id: $(this).attr('id'),
                title: "Enter or select an address from the map.",
                btnClass: "btn btn-primary btn-md",
                center: {
                    lat: 9.082,
                    lng: 8.6753
                },
                zoom: 8,
                success:function(data,address){
                    $('#delivery-address').val(data.formatted_address).trigger('change');
                }
        });

        $('#logistic_company').change(function() {
            const rate = $(this).children('option:selected').attr('rate');
            const distance = $('#distance').text().slice(0, -3);

            if (distance != null) {
                const fee = distance * rate;
                $('#fee').empty().text(fee.toFixed(2));
                $('#request_delivery').removeAttr('disabled');
            }
        });

        $('#delivery-address').change(function() {
                const delivery = $('#delivery-address').val();
                const pickup = "{{ $order->product->owner->$role->location }}";
                const date = $('#date').val();

                $.ajax({

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("distance.calculate") }}',
                    type: 'POST',
                    data: {
                        delivery: delivery,
                        pickup: pickup
                    },
                    beforeSend: function () {
                        swal.fire({
                            title: 'Calculating Distance and Price',
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                swal.showLoading()
                            },
                        });
                    },
                    success: function (data) {

                        if (data.status == 1) {

                            swal.close();

                            $('#distance').empty().text(data.distance+" KM");

                            const rate = $('#logistic_company').children('option:selected').attr('rate');
                            const distance = $('#distance').text().slice(0, -3);

                            if (rate != null) {
                                const fee = distance * rate;
                                $('#fee').empty().text(fee.toFixed(2));
                            }

                        } else {
                            swal.close();

                            setTimeout(function () {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Error ',
                                    text: "Error Fetching Distance",
                                    timer: 5000
                                }).then((value) => {}).catch(swal.noop)
                            }, 1000);
                        }
                    },
                    error: function (xhr, status, error) {
                        //other stuff
                        swal.close();

                        setTimeout(function () {
                            swal.fire({
                                icon: 'error',
                                title: 'Error ',
                                text: "Error Fetching Distance",
                                timer: 5000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        $('button').removeAttr('disabled');
                    }
                });
        });

        $('#request_delivery').click(function() {
            $(this).attr('disabled', 'disabled');
            const rate = $('#logistic_company').children('option:selected').attr('rate');
            const distance = $('#distance').text().slice(0, -3);
            const date = $('#date').val();

            if (!rate || distance == '' || date == '') {

                swal.close();

                setTimeout(function () {
                    swal.fire({
                        icon: 'error',
                        title: 'Error ',
                        text: "Kindly fill all inputs to continue",
                        timer: 5000
                    }).then((value) => {}).catch(swal.noop)
                }, 1000);

                $(this).removeAttr('disabled');

                return false;

            }

            const logistic_company = $('#logistic_company').val();
            const fee = $('#fee').text();
            const order_id = "{{ $order->uuid }}";
            const destination = $('#delivery-address').val();
            const pickup_location = "{{ $order->product->owner->$role->location }}";
            const details = $('#details').val();

            var delivery_request = {
                order_id: order_id,
                logistic_company: logistic_company,
                rate: rate,
                distance: distance,
                fee: fee,
                pickup: pickup_location,
                destination: destination,
                date: date,
                details: details
            };


            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delivery.request") }}',
                type: 'POST',
                data: {
                    delivery_request: delivery_request
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Making Delivery Request',
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            swal.showLoading()
                        },
                    });
                },
                success: function (data) {
                    //stuff
                    swal.close();

                    if (data.status == 1) {
                        setTimeout(function () {
                            swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.msg,
                                timer: 10000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        setTimeout(() => {
                            window.location.replace("{{ url('orders/') }}");
                        }, 5000);

                    } else {

                        swal.close();
                        setTimeout(function () {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: data.msg,
                                timer: 5000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        setTimeout(function () {
                            location.reload(true);
                        }, 3000);

                        $('button').removeAttr('disabled');
                    }
                },
                error: function (xhr, status, error) {
                    //other stuff
                    swal.close();

                    setTimeout(function () {
                        swal.fire({
                            icon: 'error',
                            title: 'Error ' + xhr.status,
                            text: xhr.responseJSON.message,
                            timer: 5000
                        }).then((value) => {}).catch(swal.noop)
                    }, 1000);

                    $('button').removeAttr('disabled');
                }
            });

        });

    });
</script>
@endpush

@endsection
