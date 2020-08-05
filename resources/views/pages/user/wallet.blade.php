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
                        <form>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input type="number" step="100" min="500" class="form-control" id="deposit-amount"
                                    aria-describedby="emailHelp" placeholder="Enter amount">
                                <small>Minimum of &#8358;500</small>
                            </div>
                            <button disabled type="button" id="credit_wallet_btn" class="btn btn-light">Make
                                Payment</button>
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
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    function makePayment(amount) {
        FlutterwaveCheckout({
            public_key: "{{ env('RAVE_TEST_PUBLIC_KEY') }}",
            tx_ref: "{{ 'FSA-'.mt_rand() }}",
            amount: amount,
            currency: "NGN",
            redirect_url: "{{ url('/wallet/confirm-deposit') }}",
            customer: {
                email: "{{ Auth::user()->email }}",
                phone_number: "{{ Auth::user()->phone }}",
                name: "{{ Auth::user()->first_name.' '.Auth::user()->last_name }}",
            },
            customizations: {
                title: "{{ env('APP_NAME') }}",
                description: "Wallet Deposit",
                logo: "{{ asset('images/logo/logo.jpeg') }}",
            },
        });
    }

    $('document').ready(function () {
        $('#deposit-amount').keyup(function () {
            const amount = $('#deposit-amount').val();
            if (amount >= 500) {
                $('#credit_wallet_btn').removeAttr('disabled');

            } else {
                $('#credit_wallet_btn').attr('disabled', 'disabled');
            }
        });

        $('#credit_wallet_btn').click(function () {
            const amount = $('#deposit-amount').val();

            makePayment(amount);
        });

    });

</script>

@endpush

@endsection
