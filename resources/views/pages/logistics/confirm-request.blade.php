@extends('layouts.farmkonnect') @section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
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
                    <h3 class="font-weight-bold text-center mt-3">View Delivery Request</h3>
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
                            <label>Client</label>
                            <p class="font-weight-bold">{{ $order->user->name }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Pickup Location</label>
                            <p class="font-weight-bold" id="pickup_location">
                                {{ $request->pickup_point }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>Destination</label>
                            <p class="font-weight-bold">{{ $request->destination }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>Distance</label>
                            <p class="font-weight-bold">{{ $request->distance." KM" }}</p>
                        </div>
                        <div class="col-md-3">
                            <label>Fee</label>
                            <p class="font-weight-bold"> &#8358;{{ $request->fee }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Additional Delivery Details</label>
                            <p class="font-weight-bold">{{ $request->details }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Expected Delivery Date</label>
                            <p class="font-weight-bold">{{ date('D, M j, Y', strtotime($order->created_at)) }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-lg btn-danger" id="decline_btn" data-id="{{ $request->uuid }}"
                                data-toggle="modal" data-target="#decline-modal">Decline Request</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-lg btn-success" id="confirm_btn" data-toggle="modal"
                                data-target="#accept-modal" data-id="{{ $request->uuid }}">Confirm Request</button>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" style="background-color: rgba(0,0,0,0.8); opacity: 1; color:black;"
                        id="decline-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Decline Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3 class="text-center">Confirm Request Decline.</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-danger" id="decline"
                                        data-id="{{ $request->uuid }}">Decline Request</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" style="background-color: rgba(0,0,0,0.8); opacity: 1; color:black;"
                        id="accept-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Accept Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h3 class="text-center">Confirm Request Acception.</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="accept"
                                        data-id="{{ $request->uuid }}">Accept Request</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
<script>
    $('document').ready(function() {

        $('#decliine_btn').click(function() {
            const uuid = $(this).attr('data-id');
        });

        $('#decline').click(function() {
            $(this).attr('disabled', 'disabled');
            const uuid = $(this).attr('data-id');
            console.log(uuid);

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("request.decline") }}',
                type: 'POST',
                data: {
                    uuid: uuid
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Declining Request',
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

                        setTimeout(function () {
                            window.location.replace("{{ url('logistics/requests') }}");
                        }, 3000);

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

        $('#accept_btn').click(function() {
            const uuid = $(this).attr('data-id');
        });

        $('#accept').click(function() {
            $(this).attr('disabled', 'disabled');
            const uuid = $(this).attr('data-id');
            console.log(uuid);

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("request.accept") }}',
                type: 'POST',
                data: {
                    uuid: uuid
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Accepting Request',
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

                        setTimeout(function () {
                            window.location.replace("{{ url('logistics/requests') }}");
                        }, 3000);

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
