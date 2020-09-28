<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FarmKonnect | Sellers Registration</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <meta name="msapplication-tap-highlight" content="no">

    <link rel="icon" type="image/png" href="{{asset('images/logo/logo.jpeg')}}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <style>
        .body {
            height: 100vh;
            /*ensures that at every point, the height of this element should be 95% of the viewport height*/
            background-image: linear-gradient(to right bottom, rgba(126, 213, 111, 0.8), rgba(40, 180, 133, 0.8)), url("../img/hero.jpg") !important;
            background-size: cover;
            /*ensures that the image width resizes automatically */
            background-position: top;
            /*ensures that the top of the image is not cropped out when resizing */
            position: relative;
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 75vh, 0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 75vh, 0 100%);
        }
        }
    </style>
</head>

<body class="hold-transition register-page" style="background: #c8e6c9">

    <div class="register-box">
        <div class="register-logo">
            <a href=""><b>Farm</b>Konnect</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body text-center">
                <p class="login-box-msg">Register a new member</p>

                <form method="POST" action="{{ route('admin.register') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" placeholder="Full name"
                            class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" autocomplete="name" required autofocus>
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
                        <input type="email" placeholder="Email"
                            class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email">
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
                        <input type="text" placeholder="Phone Number"
                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone') }}" required autocomplete="phone">
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
                        <select id="position" class="form-control @error('position') is-invalid @enderror"
                            name="position" required>
                            <option value="">--Please select a your role--</option>
                            <option {{old('position')=='1' ? 'selected' : ''}} value="1">Farm Owner/Farm Manager
                            </option>
                            <option {{old('position') == '2' ? 'selected' : ''}} value="2">Commodity Distributor
                            </option>
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
                    <div class="input-group mb-3">
                        <input type="password" placeholder="Password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Retype password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="social-auth-links text-center">
                        <button class="btn btn-block btn-success">
                            <i class="fas fa-check mr-2"></i>
                            Sign Up
                        </button>
                    </div>
                    {{-- <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div> --}}
                </form>

                <a href="{{route('admin.login')}}" class="text-center">I am already a member</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->


    {{-- Js comes in here --}}
</body>

</html>
