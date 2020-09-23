@extends('admin.layouts.layout')

@section('content')

<div class="card">
    <div class="card-body register-card-body text-center">
        <p class="login-box-msg">Register a new member</p>

        <form method="POST" action="{{ route('admin.add-user') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="text" placeholder="Full name" class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name') }}" autocomplete="name" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message}}</strong>
                </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="text" placeholder="Phone Number" class="form-control @error('phone') is-invalid @enderror"
                    name="phone" value="{{ old('phone') }}" required autocomplete="phone">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-telephone"></span>
                    </div>
                </div>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <select id="position" class="form-control @error('position') is-invalid @enderror" name="position"
                    required>
                    <option value="">--Please select a role--</option>
                    <option {{old('position')=='1' ? 'selected' : ''}} value="1">Farm Owner/Farm Manager</option>
                    <option {{old('position') == '2' ? 'selected' : ''}} value="2">Commodity Distributor</option>
                    <option {{old('position') == '3' ? 'selected' : ''}} value="3">Commodity Retailer</option>
                    <option {{old('position') == '4' ? 'selected' : ''}} value="4">Commodity Consumer</option>
                    <option {{old('position') == '5' ? 'selected' : ''}} value="5">Logistic Company</option>
                </select>

                @error('position')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="social-auth-links text-center">
                <button class="btn btn-block btn-success">
                    <i class="fas fa-check mr-2"></i>
                    Sign Up
                </button>
            </div>

        </form>
    </div>
    <!-- /.form-box -->
</div>
<!--
@endsection
