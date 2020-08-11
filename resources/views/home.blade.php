@extends('layouts.farmkonnect')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Home</li>
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
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>0</h3>

                        <p>Delivery Request</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box navbar-teal">
                    <div class="inner">
                        <h3>&#8358; 44</h3>

                        <p>Wallet Balance</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box default-primary-color">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Unique Visitors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@push('scripts')
<script>
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip(); //Tooltip on icons top

        $('.popoverOption').each(function () {
            var $this = $(this);
            $this.popover({
                trigger: 'hover',
                placement: 'left',
                container: $this,
                html: true,

            });
        });
    });
    $(document).ready(function () {
        if (!getCookie("step_dashboard")) {
            console.log('executed');
            $("#dashboard").addClass("in");
            // Instance the tour
            var tour = new Tour({
                storage: false,
                backdrop: true,
                smartPlacement: true,
                steps: [{
                        element: "body",
                        title: "{{trans('Sellers App')}}",
                        content: "{{trans('Welcome to FarmKonnect Sellers App.Let\'s have a one time quick tour.')}}",
                        placement: 'top'
                    },
                    {
                        element: ".col-lg-12",
                        title: "{{trans('Dashboard')}}",
                        content: "{{trans('This is your dashboard, which you can use to get a fast and nice overview, of all your tasks, deliveries, commdities, etc.')}}",
                        placement: 'top'
                    },
                    {
                        element: "#myNavMenu",
                        title: "{{trans('Navigation')}}",
                        content: "{{trans('This is your primary navigation bar, which you can use to get around Sellers APP ')}}"
                    }
                ]
            });
            tour.addSteps([{
                    placement: top,
                    smartPlacement: true
                },
                {
                    element: ".content-wrapper",
                    title: "{{trans('Demographics')}}",
                    content: "{{trans('Infographics showing the summary of activities')}}"
                },
                {
                    path: '/home'
                }
            ])
            var userAuth = '{{ auth()->user()}}';
            if (userAuth) {
                tour.addSteps([{
                        element: "#profile",
                        title: "{{trans('Create Your Profile')}}",
                        content: "{{trans('Let\'s take our first step, by creating a Profile')}}"
                    },
                    {
                        path: '/profile'
                    }
                ])
            }

            // Initialize the tour
            tour.init();

            tour.start();
            setCookie("step_dashboard", true, 1000)
        }
        

    });

</script>
@endpush
