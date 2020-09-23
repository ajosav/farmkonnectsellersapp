@extends('admin.layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Commodity Consumer Profiles</h1>
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
            @if($consumers->isEmpty())
            <p class="text-center">No commodity consumer profile made yet.</p>
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
                        @foreach($consumers as $consumer)
                        <tr id="{{ $consumer->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td>{{ $consumer->name }}</td>
                            <td>{{ $consumer->location }}</td>
                            <td>{{ $consumer->address }}</td>
                            <td>{{ $consumer->state }}</td>
                            <td>{{ $consumer->lg }}</td>
                            <td><a href="tel:{{ $consumer->phone }}">{{ $consumer->phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $consumer->email }}">{{ $consumer->email }}</a>
                            </td>
                            <td>{!! $consumer->other_info !!}</td>
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
