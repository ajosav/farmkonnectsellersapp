@extends('admin.layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Commodity Retailer Profiles</h1>
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
            @if($retailers->isEmpty())
            <p class="text-center">No commodity Retailer profile made yet.</p>
            @else
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>State</th>
                            <th>L.G.A</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                            <th>Other Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($retailers as $retailer)
                        <tr id="{{ $retailer->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td>{{ $retailer->name }}</td>
                            <td>{{ $retailer->location }}</td>
                            <td>{{ $retailer->address }}</td>
                            <td>{{ $retailer->state }}</td>
                            <td>{{ $retailer->lg }}</td>
                            <td><a href="tel:{{ $retailer->phone }}">{{ $retailer->phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $retailer->email }}">{{ $retailer->email }}</a>
                            </td>
                            <td>{!! $retailer->other_info !!}</td>
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
            "pageLength": 10
        });

    });

</script>
@endpush
