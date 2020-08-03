@extends('layouts.farmkonnect')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header" id='user_profile'>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Create Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="breadcrumb-item active">Product</li>
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
            <div class="row"  id="user_info">

                @can('Farm Manager')
                    <create-product></create-product>
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

</script>

@endpush
