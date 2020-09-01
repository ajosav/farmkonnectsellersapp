@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Order History</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Home\Order History</li>
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
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th>Action</th>
                            <th>Date and Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @foreach($orders as $order)
                        <tr id="{{ $order->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td class="product">{{ $order->product->name }}</td>
                            <td class="quantity">{{ $order->quantity_ordered.' '.$order->unit->unit_name.'(s)' }}</td>
                            <td class="price">&#8358;{{ $order->total_price }}</td>
                            <td class="vendor"><a
                                    class="btn btn-link text-success">{{ $order->product->owner->$role->contact_person }}</a>
                            </td>
                            <td class="status">
                                @if($order->status == 0)
                                <span class="badge badge-danger">Declined</span>
                                @elseif($order->status == 1)
                                <span class="badge badge-success">Confirmed by Vendor</span>
                                @elseif($order->status == 2)
                                <span class="badge badge-warning">Pending</span>
                                @elseif($order->status == 4)
                                <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $order->feedback }}</td>
                            <td>
                                @if($order->status == 2)
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" data-toggle="modal"
                                    data-target="#exampleModalCenter" data-id="{{ $order->uuid }}">
                                    Cancel Order
                                </button>
                                @elseif($order->status == 1)
                                <button class="btn btn-sm btn-primary" class="btn btn-sm btn-danger cancel-btn"
                                    data-toggle="modal" data-target="#pickup-modal" data-id="{{ $order->uuid }}">Request
                                    Pickup</button>
                                @endif
                            </td>
                            <td>{{ date('D, M j, Y \a\t g:ia', strtotime($order->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>Summary</th>
                        <th></th>
                        <th colspan='3'> Total Orders Made - {{ count($orders) }} </th>
                    </tfoot>
                </table>
                <!-- /.box-body -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" style="opacity: 1;" id="exampleModalCenter" tabindex="-1" role="dialog"
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
                        <h3 class="text-center">Order Cancellation.</h3>
                        <div class="text-center">
                            <label>Product</label>
                            <p id="product"></p>
                        </div>
                        <div class="text-center">
                            <label>Quantity Ordered</label>
                            <p id="quantity"></p>
                        </div>
                        <div class="text-center">
                            <label>Vendor</label>
                            <p id="vendor"></p>
                        </div>
                        <div class="text-center">
                            <label>Total Price</label>
                            <p id="price"></p>
                        </div>
                        <p class="text-center font-weight-bold">Once cancelled, this order will not be fufilled by the
                            vendor.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="confirm-cancellation">Confirm
                            Cancellation</button>
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
    $('document').ready(function () {
        $('table').dataTable({
            "pageLength": 10
        });

        $('.cancel-btn').click(function() {

            const order_uuid = $(this).attr('data-id');
            var row = $(this).closest("tr");

            var product = row.find('.product').text();
            var quantity = row.find('.quantity').text();
            var price = row.find('.price').text();
            var vendor = row.find('.vendor').text();

            $('#product').empty().html(product);
            $('#quantity').empty().html(quantity);
            $('#price').empty().html(price);
            $('#vendor').empty().html(vendor);

            $('#confirm-cancellation').removeAttr('data-id').attr('data-id', order_uuid);
        });

        $('#confirm-cancellation').click(function() {

            const order_uuid = $(this).attr('data-id');

            $(this).attr('disabled', 'disabled');

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("order.cancel") }}',
                type: 'POST',
                data: {
                    order_uuid: order_uuid
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Cancelling Order',
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
                            location.reload(true);
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
