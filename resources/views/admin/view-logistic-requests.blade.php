use App\Model\Delivery;
@extends('admin.layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Logistics Requests</h1>
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
                            <td class="status" data-status="{{ $request->status }}">
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
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('scripts')
<script>
    $('document').ready(function() {
        $('table').dataTable({
            "pageLength": 50
        });

    });

</script>
@endpush
