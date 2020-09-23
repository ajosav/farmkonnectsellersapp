@extends('admin.layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Farm Manager Profiles</h1>
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
            @if($managers->isEmpty())
            <p class="text-center">No farm manager profile made yet.</p>
            @else
            <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Contact Person</th>
                            <th>Location</th>
                            <th>Farm Size</th>
                            <th>Commodities Planted</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($managers as $manager)
                        <tr id="{{ $manager->uuid }}">
                            <td>{{ ++$count }}</td>
                            <td>{{ $manager->contact_person }}</td>
                            <td>{{ $manager->location }}</td>
                            <td>{{ $manager->farm_size }}</td>
                            <td>
                                {{ $manager->commodities_planted }}
                            </td>
                            <td><a href="tel:{{ $manager->c_person_phone }}">{{ $manager->c_person_phone }}</a></td>
                            <td>
                                <a href="mailto:{{ $manager->c_person_email }}">{{ $manager->c_person_email }}</a>
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
            "pageLength": 10
        });

    });

</script>
@endpush
