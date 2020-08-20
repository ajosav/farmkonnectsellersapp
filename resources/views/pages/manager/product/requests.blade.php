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
                        <tr>
                            <td>{{ ++$count }}</td>
                            <td>{{ $request->user->$role->name }}</td>
                            <td>{{ $request->product->name }}</td>
                            <td>{{ $request->quantity_ordered.' '.$request->unit->unit_name.'(s)' }}</td>
                            <td>{{ $request->total_price }}</td>
                            <td>
                                <button class="btn btn-sm btn-success">Accept</button>
                                <button class="btn btn-sm btn-danger">Decline</button>
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
            @endif
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
