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
                        <tr>
                            <td>{{ ++$count }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>&#8358;{{ $order->total_price }}</td>
                            <td><a
                                    class="btn btn-link text-success">{{ $order->product->owner->$role->contact_person }}</a>
                            </td>
                            <td>
                                @if($order->status == 0)
                                <span class="badge badge-danger">Failed</span>
                                @elseif($order->status == 1)
                                <span class="badge badge-success">Successful</span>
                                @elseif($order->status == 2)
                                <span class="badge badge-warning">Pending</span>
                                @elseif($order->status == 4)
                                <span class="badge badge-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>{{ $order->feedback }}</td>
                            <td>
                                @if($order->status == 2)
                                <button class="btn btn-sm btn-danger">Cancel Order</button>
                                @elseif($order->status == 1)
                                <button class="btn btn-sm btn-primary">Request Pickup</button>
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
    });

</script>
@endpush
