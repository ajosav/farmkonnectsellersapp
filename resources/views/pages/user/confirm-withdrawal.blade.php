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
                        <h3 class="text-center">Confirm Withdrawal Details</h3>
                        <label for="recipient_name">Recipient Name</label>
                        <h4 class="font-weight-bold" id="recipient_name">{{ Auth::user()->bank_account->account_name }}
                        </h4>
                        <label for="bank_name">Bank Name</label>
                        <h4 class="font-weight-bold" id="bank_name">{{ AUth::user()->bank_account->bank_name }}</h4>
                        <label for="account_number">Account Number</label>
                        <h4 class="font-weight-bold" id="account_number">
                            {{ Auth::user()->bank_account->account_number }}</h4>
                        <label for="account_number">Amount</label>
                        <h4 class="font-weight-bold" id="account_number">&#8358;{{ $transaction->amount }}</h4>
                        <div class="container">
                            <button class="btn btn-md btn-dark float-left" id="back">Go Back</button>
                            <button class="btn btn-md btn-light float-right" id="confirm-withdrawal">Withdraw</button>
                        </div>
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

        $('#back').click(function () {
            history.go(-1);
            return false;
        });


        $('#confirm-withdrawal').click(function (e) {
            e.preventDefault();

            const amount = '{{ $transaction->amount }}';
            const account_number = '{{ Auth::user()->bank_account->account_number }}';
            const account_uuid = '{{ Auth::user()->bank_account->uuid }}';
            const account_bank = '{{ Auth::user()->bank_account->account_bank }}';
            const account_name = '{{ Auth::user()->bank_account->account_name }}';

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url("wallet/withdraw-money") }}',
                type: 'POST',
                data: {
                    account_number: account_number,
                    account_uuid: account_uuid,
                    amount: amount,
                    account_bank: account_bank,
                    account_name: account_name
                },
                beforeSend: function () {
                    swal.fire({
                        title: 'Processing Withdrawal',
                        onBeforeOpen: () => {
                            swal.showLoading()
                        },
                    });
                },
                success: function (data) {
                    //stuff
                    swal.close();

                    if (data.status == 1) {
                        setTimeout(function () {
                            swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.msg,
                                timer: 100000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        setTimeout(function () {
                            window.location = '{{ url("/wallet") }}';
                        }, 5000);

                    } else {
                        swal.close()
                        setTimeout(function () {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: data.msg,
                                timer: 50000
                            }).then((value) => {}).catch(swal.noop)
                        }, 1000);

                        setTimeout(function () {
                            window.location =
                                '{{ url("/transactions/wallet") }}';
                        }, 500000);
                    }
                },
                error: function (xhr, status, error) {
                    //other stuff
                    swal.close();
                    console.log(xhr.responseJSON.message);
                    setTimeout(function () {
                        swal.fire({
                            icon: 'error',
                            title: 'Error ' + xhr.status,
                            text: xhr.responseJSON.message,
                            timer: 50000
                        }).then((value) => {}).catch(swal.noop)
                    }, 1000);

                    $(this).removeAttr('disabled');
                }
            });
        });
    });

</script>

@endpush

@endsection
