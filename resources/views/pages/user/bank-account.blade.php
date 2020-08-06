@extends('layouts.farmkonnect')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

</style>
@endpush

@section('content')

@php
$status = $fetch_banks->status;
@endphp
@if($status != 200)
$error = $fetch_banks->error;
@else
@php
$banks = $fetch_banks->list;
$banks = json_decode($banks);
@endphp
@endif

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
                    <h4 class="card-header">Bank Account</h4>
                    <div class="card-body">
                        <form action="{{ route('bank-account') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="Account Name">Account Name</label>
                                <input value="<?= @$account->account_name; ?>" readonly type="text" class="form-control"
                                    aria-describedby="emailHelp" placeholder="Enter account name" name="account_name">
                            </div>
                            <div class="form-group">
                                <label for="Account Name">Bank Name</label>
                                @isset($error)
                                <small class="text-danger text-center">{{ $error }}</small>
                                @endisset
                                <select class="form-control" id="account_bank" name="account_bank">
                                    @if($account)
                                    <option selected value="{{ $account->account_bank }}"
                                        data-name="{{ $account->bank_name }}">
                                        {{ $account->bank_name }}</option>
                                    @endif
                                    @isset($banks)
                                    @foreach($banks->data as $bank)
                                    <option value="{{ $bank->code }}" data-name="{{ $bank->name }}">
                                        {{ $bank->name }}</option>
                                    @endforeach
                                    <input type="hidden" value="<?= @$account->bank_name ?>" id="bank_name"
                                        name="bank_name">
                                    @endisset</div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Account Number</label>
                                <input name="account_number" value="<?= @$account->account_number; ?>" type="number"
                                    class="form-control" aria-describedby="emailHelp"
                                    placeholder="Enter account number">
                            </div>
                            <button type="submit" class="btn btn-light">Save Account Details</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('select').select2({
            placeholder: "Select Bank",
            allowClear: true,
            width: '100%'
        });

        $('#account_bank').change(function () {
            var bank_name = $(this).children("option:selected").attr('data-name');
            $('#bank_name').val('');
            $('#bank_name').val(bank_name);
        });
    });

</script>

@endpush

@endsection
