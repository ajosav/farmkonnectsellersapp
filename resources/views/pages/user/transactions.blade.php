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
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Transaction Id</th>
                            <th>Transaction Reference</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Title</th>
                            <th>Narration</th>
                            <th>Status</th>
                            <th>Date and Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ ++$count }}</td>
                            <td>{{ $transaction->uuid }}</td>
                            <td>{{ $transaction->txn_ref }}</td>
                            <td>
                                @if($transaction->type == 'Credit')
                                <span class="badge badge-success">Credit</span>
                                @else
                                <span class="badge badge-danger">Debit</span>
                                @endif
                            </td>
                            <td>&#8358;{{ $transaction->amount }}</td>
                            <td>{{ $transaction->title }}</td>
                            <td>{{ $transaction->narration }}</td>
                            <td>
                                @if($transaction->status == 1)
                                <span class="badge badge-success">Successful</span>
                                @elseif($transaction->status == 0)
                                <span class="badge badge-danger">Failed</span>
                                @else
                                <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ date('D, M j, Y \a\t g:ia', strtotime($transaction->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>Summary</th>
                        <th></th>
                        <th colspan='3'> Total Confirmed Credit - &#8358;{{ $total_credit }} </th>
                        <th colspan='3'> Total Confirmed Debit - &#8358;{{ $total_debit }} </th>
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
