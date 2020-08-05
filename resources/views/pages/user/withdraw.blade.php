@extends('layouts.farmkonnect') @section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Home</li>\
                    <li class="active">Wallet</li>
                </ol>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row" id="summary">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-center">
                <div class="card text-white bg-success mb-3">
                    <h4 class="card-header">Wallet Balance</h4>
                    <h3 class="font-weight-bold mt-3">&#8358; {{ number_format(Auth::user()->wallet->balance, 2) }}</h3>
                    <div class="card-body">
                        <form method="POST" action="{{ route('withdraw') }}">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input type="number" min="1000" class="form-control" id="withdraw-amount"
                                    aria-describedby="emailHelp" max="{{ Auth::user()->wallet->balance }}"
                                    placeholder="Enter amount" name="amount">
                                <small>Minimum of &#8358;1000</small>
                            </div>
                            <button disabled type="submit" id="continue_btn" class="btn btn-light">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@push('scripts')
<script>
    $('document').ready(function () {
        $('#withdraw-amount').keyup(function () {
            const amount = $('#withdraw-amount').val();
            if (amount >= 1000) {
                $('#continue_btn').removeAttr('disabled');

            } else {
                $('#continue_btn').attr('disabled', 'disabled');
            }
        });
    });

</script>

@endpush

@endsection
