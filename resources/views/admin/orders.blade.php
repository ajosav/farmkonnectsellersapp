@extends('admin.layouts.layout')

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
                    {{-- <li class="breadcrumb-item active">Home\Order History</li> --}}
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
                            <th>Buyer</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Feedback</th>
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
                            <td class="buyer">{{ $order->user->name }}</td>
                            <td class="vendor"><a
                                    class="btn btn-link text-success">{{ $order->product->owner->user->name }}</a>
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
                            <td>{{ date('D, M j, Y \a\t g:ia', strtotime($order->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.box-body -->
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script src="{{ asset('js/place-picker.js') }}"></script>
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
