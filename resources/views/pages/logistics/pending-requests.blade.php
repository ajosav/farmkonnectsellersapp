@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Pending Requests</h1>
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
            @if($requests->isEmpty())
            <p class="text-center">No delivery requests made yet.</p>
            @else
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Customer Name</th>
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
                        @foreach($requests as $request)
                        <tr id="{{ $request->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td>{{ $request->order($request->order_id)->user->name }}</td>
                            <td>{{ $request->order($request->order_id)->product->name }}</td>
                            <td>
                                {{ $request->order($request->order_id)->quantity_ordered.' '.$request->order($request->order_id)->unit->unit_name.'(s)' }}
                            </td>
                            <td>{{ $request->pickup_point }}</td>
                            <td>
                                {{ $request->destination }}
                            </td>
                            <td>{{ $request->distance." KM" }}</td>
                            <td>&#8358;{{ $request->fee }}</td>
                            <td>
                                @if($request->status == 1)
                                <span class="badge badge-info">Awaiting Pickup</span>
                                @elseif($request->status == 6)
                                <span class="badge badge-success">Delivery Complete</span>
                                @elseif($request->status == 3)
                                <span class="badge badge-warning">Pickup Complete</span>
                                @elseif($request->status == 4)
                                <span class="badge badge-light">Enroute to Destination</span>
                                @elseif($request->status == 5)
                                <span class="badge badge-dark">Awaiting Delivery Confirmation</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" style="color:white;" data-toggle="modal"
                                    data-target="#exampleModal" id="update_btn" data-id="{{ $request->uuid }}">Update
                                    Status</button>
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

            <!-- Decline Modal -->
            <div class="modal fade" style="opacity: 1;" id="decline-modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Cancel Order</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 class="text-center">Decline Request</h3>
                            <div class="text-center">
                                <label>Product</label>
                                <p id="product"></p>
                            </div>
                            <div class="text-center">
                                <label>Quantity Ordered</label>
                                <p id="quantity"></p>
                            </div>
                            <div class="text-center">
                                <label>Customer</label>
                                <p id="customer"></p>
                            </div>
                            <div class="text-center">
                                <label>Total Price</label>
                                <p id="price"></p>
                            </div>
                            <div class="text-center">
                                <label>Feedback</label>
                                <textarea id="feedback" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" id="confirm-decline">Confirm
                                Decline</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Accept Modal -->
            <div class="modal fade" style="opacity: 1;" id="accept-modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Cancel Order</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 class="text-center">Accept Request</h3>
                            <div class="text-center">
                                <label>Product</label>
                                <p id="accept_product"></p>
                            </div>
                            <div class="text-center">
                                <label>Quantity Ordered</label>
                                <p id="accept_quantity"></p>
                            </div>
                            <div class="text-center">
                                <label>Customer</label>
                                <p id="accept_customer"></p>
                            </div>
                            <div class="text-center">
                                <label>Total Price</label>
                                <p id="accept_price"></p>
                            </div>
                            <div class="text-center">
                                <label>Feedback</label>
                                <textarea id="accept_feedback" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="confirm-accept">Confirm
                                Request</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" style="opacity: 1; background-color: rgba(0,0,0,0);" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Set Delivery Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    <select id="status">
                                        <option value="1">Awaiting Pickup</option>
                                        <option value="3">Pickup Complete</option>
                                        <option value="4">Enroute to Destination</option>
                                        <option value="5">Awaiting Delivery Confirmation</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="update">Update Status</button>
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


        $('#update_btn').click(function() {

            const uuid = $(this).attr('data-id');

            $('#update').attr('data-id', uuid);
        });

        $('#update').click(function() {


            const uuid = $(this).attr('data-id');

            const status = $('#status').val();

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("request.update") }}',
                type: 'POST',
                data: {
                    uuid: uuid,
                    status: status
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
                            window.location.replace("{{ url('logistics/pending-requests') }}");
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
