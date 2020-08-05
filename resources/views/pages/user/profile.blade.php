@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header" id='user_profile'>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Profile</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-dashboard"></i>
                    Home</a></li>
                    <li class="breadcrumb-item active">Profile</li> --}}
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
        <div class="row" id="user_info">

            @can('Farm Manager')
            <manager-profile :status="{{$status}}"></manager-profile>
            @elsecan('Commodity Distributor')
            <commodity-distributor :status="{{$status}}"></commodity-distributor>
            @elsecan('Commodity Retailer')
            <commodity-retailer :status="{{$status}}"></commodity-retailer>
            @elsecan('Commodity Consumer')
            <commodity-consumer :status="{{$status}}"></commodity-consumer>
            @elsecan('Logistic Company')
            <logistic-company :status="{{$status}}"></logistic-company>
            @endcan
            <div class="table-responsive">
                {{-- <table id="userTable" class="table table-bordered table-striped dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Intro</th>
                                <th>Created At</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                    </table> --}}
                <!-- /.box-body -->


                {{-- {!! $dataTable->table()!!} --}}
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('scripts')

<script>
    $(document).ready(function () {
        var status = '{{$status == '
        false '}}';
        if (status) {
            $("#profile").addClass("in");
            // Instance the tour
            var tour = new Tour({
                storage: false,
                backdrop: true,
            });
            tour.addStep({
                element: "#user_profile",
                title: "{{trans("
                Profile ")}}",
                content: "{{trans("
                Lets start by creating a profile
                for you ")}}",
                placement: 'top'
            })
            // Initialize the tour

            tour.addStep({
                element: "#user_info",
                title: "{{trans("
                Personal Profile ")}}",
                content: "{{trans("
                Please complete your profile by filling these fields ")}}"
            })
            // if (canCreateProject) {
            //     tour.addStep({
            //         element: "#newProject",
            //         title: "{{trans("Create project")}}",
            //         content: "{{trans("Projects are used to keep track of tasks that might be related to a bigger assignment for the client. And gives the possibility of multiple people working various tasks and keep track of the tasks.")}}",
            //     })
            // }
            tour.init();

            tour.start();
        }

    });

</script>


{{-- <script>
        $(document).ready(function() {
            $('#userTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('get.users') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    // {data: 'DT_RowData.data-name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'position', name: 'position'},
                    {data: 'intro', name: 'Intro'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action'},
                ]
            })
        } );

    </script> --}}
{{-- {!! $dataTable->scripts()!!} --}}
@endpush
