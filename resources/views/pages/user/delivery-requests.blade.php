@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Delivery Requests</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" id="summary">
            @if($deliveries->isEmpty())
            <p class="text-center">No delivery requests made yet.</p>
            @else
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Logistics Company</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Pickup Point</th>
                            <th>Destination</th>
                            <th>Distance</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @foreach($deliveries as $delivery)
                        <tr id="{{ $delivery->delivery_uuid }}">
                            <td>{{ ++$count }}</td>
                            <td>{{ $delivery->order($delivery->order_id)->user->name }}</td>
                            <td>{{ $delivery->order($delivery->order_id)->product->name }}</td>
                            <td>
                                {{ $delivery->order($delivery->order_id)->quantity_ordered.' '.$delivery->order($delivery->order_id)->unit->unit_name.'(s)' }}
                            </td>
                            <td>{{ $delivery->pickup_point }}</td>
                            <td>
                                {{ $delivery->destination }}
                            </td>
                            <td>{{ $delivery->distance." KM" }}</td>
                            <td>&#8358;{{ $delivery->fee }}</td>
                            <td class="status" data-status="{{ $delivery->status }}">
                                @if($delivery->delivery_status == 1)
                                <span class="badge badge-info">Awaiting Pickup</span>
                                @elseif($delivery->delivery_status == 0)
                                <span class="badge badge-danger">Request Rejected</span>
                                @elseif($delivery->delivery_status == 6)
                                <span class="badge badge-success">Delivery Complete</span>
                                @elseif($delivery->delivery_status == 3)
                                <span class="badge badge-warning">Pickup Complete</span>
                                @elseif($delivery->delivery_status == 4)
                                <span class="badge badge-light">Enroute to Destination</span>
                                @elseif($delivery->delivery_status == 5)
                                <span class="badge badge-dark">Awaiting Delivery Confirmation</span>
                                @endif
                            </td>
                            <td>
                                @if($delivery->delivery_status == 5)
                                <button class="btn btn-primary btn-sm" id="confirm_delivery_btn"
                                    data-id="{{ $delivery->delivery_id }}">Confirm
                                    Delivery</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>Summary</th>
                    </tfoot>
                </table>
                <!-- /.box-body -->
            </div>

            @endif
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" style="opacity: 1; background-color: rgba(0,0,0,0);" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delivery Completion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="confirm">Confirm Delivery</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script>
    $('document').ready(function() {
        $('table').dataTable({
            "pageLength": 10
        });

        $('#confirm_delivery_btn').click(function(e) {

            e.preventDefault();

            const uuid = $(this).attr('data-id');

            console.log(uuid);

            $('#confirm').attr('data-id', uuid);

            $('#exampleModal').modal('show');

        });


        $('#confirm').click(function() {

            $(this).attr('disabled', 'disabled');

            const uuid = $(this).attr('data-id');

            console.log(uuid);

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("delivery.request.confirm") }}',
                type: 'POST',
                data: {
                    uuid: uuid
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Updating Request',
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
                            window.location.reload(true);
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
