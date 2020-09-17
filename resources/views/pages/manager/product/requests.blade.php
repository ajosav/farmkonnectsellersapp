@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transactions</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Home\Transactions</li>
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
            <p class="text-center">No requests made yet.</p>
            @else
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Customer Name</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Action</th>
                            <th>Date Ordered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @foreach($requests as $request)
                        <tr id="{{ $request->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td class="customer">{{ $request->user->$role->name }}</td>
                            <td class="product">{{ $request->product->name }}</td>
                            <td class="quantity">{{ $request->quantity_ordered.' '.$request->unit->unit_name.'(s)' }}
                            </td>
                            <td class="price">{{ $request->total_price }}</td>
                            <td>
                                <button class="btn btn-sm btn-success accept-offer" data-toggle="modal"
                                    data-target="#accept-modal" data-id="{{ $request->uuid }}">Accept</button>
                                <button class=" btn btn-sm btn-danger decline-offer" data-toggle="modal"
                                    data-target="#decline-modal" data-id="{{ $request->uuid }}">Decline</button>
                            </td>
                            <td>{{ date('D, M j, Y \a\t g:ia', strtotime($request->created_at)) }}</td>
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

        $('.decline-offer').click(function() {

            const order_uuid = $(this).attr('data-id');
            var row = $(this).closest("tr");

            var product = row.find('.product').text();
            var quantity = row.find('.quantity').text();
            var price = row.find('.price').text();
            var customer = row.find('.customer').text();

            $('#product').empty().html(product);
            $('#quantity').empty().html(quantity);
            $('#price').empty().html(price);
            $('#customer').empty().html(customer);

            $('#confirm-decline').removeAttr('data-id').attr('data-id', order_uuid);

        });

        $('#confirm-decline').click(function() {

            $(this).attr('disabled', 'disabled');

            const uuid = $(this).attr('data-id');

            const feedback = $('#feedback').val();

            if (feedback == '') {

                setTimeout(function() {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Kindly Include a feedback note for the customer.',
                        timer: 5000
                    }).then((value) => {}).catch(swal.noop)
                }, 1000);

                $(this).removeAttr('disabled');

                return false;
            }

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("request.decline") }}',
                type: 'POST',
                data: {
                    uuid: uuid,
                    feedback: feedback
                },
                beforeSend: function() {
                    swal.fire({
                        title: 'Processing',
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            swal.showLoading()
                        },
                        });
                },
                success: function(data) {

                    swal.close();

                    if (data.status == 1) {
                        setTimeout(function() {
                            swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.msg,
                                timer: 3000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);


                        $('#' + uuid).remove();

                        $('.modal').modal('hide');

                        setInterval(() => {
                            location.reload(true);
                        }, 2000);


                        $('button').removeAttr('disabled');

                    } else {

                        swal.close();
                        setTimeout(function() {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: data.msg,
                                timer: 5000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        $('button').removeAttr('disabled');
                    }
                }
                , error: function(xhr, status, error) {
                    //other stuff
                    swal.close();

                    setTimeout(function() {
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

        $('.accept-offer').click(function() {

            const order_uuid = $(this).attr('data-id');
            var row = $(this).closest("tr");

            var product = row.find('.product').text();
            var quantity = row.find('.quantity').text();
            var price = row.find('.price').text();
            var customer = row.find('.customer').text();

            $('#accept_product').empty().html(product);
            $('#accept_quantity').empty().html(quantity);
            $('#accept_price').empty().html(price);
            $('#accept_customer').empty().html(customer);

            $('#confirm-accept').removeAttr('data-id').attr('data-id', order_uuid);

        });


        $('#confirm-accept').click(function() {

            $(this).attr('disabled', 'disabled');

            const uuid = $(this).attr('data-id');

            const feedback = $('#accept_feedback').val();

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route("request.accept") }}',
                type: 'POST',
                data: {
                    uuid: uuid,
                    feedback: feedback
                },
                beforeSend: function() {
                    swal.fire({
                        title: 'Processing',
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            swal.showLoading()
                        },
                        });
                },
                success: function(data) {

                    swal.close();

                    if (data.status == 1) {

                        setTimeout(function() {
                            swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.msg,
                                timer: 5000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        $('.modal').modal('hide');

                        setTimeout(() => {
                            location.reload(true);
                        }, 3000);

                    } else {

                        swal.close();
                        setTimeout(function() {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: data.msg,
                                timer: 5000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        $('button').removeAttr('disabled');
                    }
                }
                , error: function(xhr, status, error) {
                    //other stuff
                    swal.close();

                    setTimeout(function() {
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
