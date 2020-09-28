<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Farmkonnect') }}</title>

    <link rel="icon" type="image/png" href="{{asset('images/logo/logo.jpeg')}}" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <style>
        @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
        @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,700,900);

        * {
            padding: 0;
            margin: 0;
        }

        input {
            outline: none;
        }

        html,
        body {
            height: 100vh;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #c8e6c9;
            /* overflow: hidden; */
        }

        .panel {
            width: 400px;
            /* border-top: 7px solid#388e3c;  */
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.25);
            box-shadow: 0 1px 4px 0px rgba(0, 0, 0, 0.25);
            background: #ffffff;
            margin: 100px auto;
            text-align: center;
            padding-bottom: 20px;
        }

        .panel .state {
            margin-top: 5px;
            width: 100%;
            height: 155px;
            color: #212121;
            font-size: 20px;
        }

        .panel .state i.fa-ban {
            font-size: 40px;
        }

        .panel .state i.fa-unlock-alt {
            font-size: 25px;
            color: #388e3c;
            line-height: 33px;
            height: 30px;
            width: 30px;
            display: inline-block;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            border: 2px solid;
        }

        .panel .state h2 {
            font-weight: 400;
        }

        .panel .form {
            width: 340px;
            margin: 5px auto;
        }

        .panel .login {
            height: 45px;
            width: 100%;
            background-color: #388e3c;
            -webkit-border-radius: 45px;
            -moz-border-radius: 45px;
            border-radius: 45px;
            position: relative;
            line-height: 45px;
            text-align: center;
            font-weight: bold;
            color: white;
            margin-top: 10px;
            -webkit-transition: background .2s;
            -moz-transition: background .2s;
            -o-transition: background .2s;
            transition: background .2s;
            cursor: pointer;
        }

        .panel .login:active {
            -webkit-transform: translateY(2px);
            -moz-transform: translateY(2px);
            -ms-transform: translateY(2px);
            -o-transform: translateY(2px);
            transform: translateY(2px);
        }

        .panel .login:hover {
            background-color: #4caf50;
        }

        .panel .login:after {
            content: "\f084";
            font-family: 'FontAwesome';
            position: absolute;
            width: 45px;
            height: 45px;
            background-color: #212121;
            color: #fff;
            left: 0;
            top: 0;
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            text-align: center;
            line-height: 45px;
        }

        .panel input[type='text'],
        input[type='password'] {
            background-color: #ffffffe0;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            border-radius: 15px;
            font-size: 15px;
            height: 45px;
            border: 1px solid #bdbdbd;
            padding-left: 15px;
            width: -webkit-calc(100% - 15px);
            width: -moz-calc(100% - 15px);
            width: calc(100% - 15px);
            margin-bottom: 20px;

        }

        .panel input[type='text'][placeholder],
        input[type='text']['placeholder'] {
            color: #656d79;
            font-size: 15px;
            font-weight: 500;

        }

        .panel .fack {
            margin-top: 30px;
            font-size: 14px;
        }

        .panel .fack i.fa {
            text-decoration: none;
            color: #212121;
            vertical-align: middle;
            font-size: 20px;
            margin-right: 5px;
        }

        .panel .fack a:link {
            color: #212121;
            ;
        }

        .panel .fack a:visited {
            color: #212121;
            ;
        }

        .is-invalid {
            box-shadow: 0px 0px 2px 1px red;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            /* padding: .375rem .75rem; */
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .error-message {
            color: #cc0033;
            display: inline-block;
            font-size: 12px;
            line-height: 15px;
            margin: 5px 0 0;
        }
    </style>
</head>

<body>
    <div class="panel">
        <div class="state"><br><i class="fa fa-unlock-alt"></i><br>
            <h1>Log In</h1>
        </div>
        <ul>
            {{-- @if(count($errors) > 0)

                    @foreach ($errors->all() as $error)
                        <li>
                            <strong>{{$error}}</strong>
            </li>
            @endforeach


            @endif --}}
        </ul>
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form">
                @if ($errors->has('phone') || $errors->has('email'))
                <div class="error-message">{{ $errors->first('phone') ?: $errors->first('email') }}</div>
                @endif
                <input placeholder='Email/Phone' type="text" id="email" type="text"
                    class="{{$errors->has('phone') || $errors->has('email') ? 'is-invalid' : ''}}" name="email_or_phone"
                    value="{{old('phone') ?: old('email') }}" required autofocus>

                @error('password')
                <div class="error-message">{{ $errors->first('password')}}</div>
                @enderror
                <input placeholder='Password' type="password" class="@error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="btn login">
                    {{ __('Login') }}
                </button>
                {{-- <div class="login">Login</div> --}}

            </div>
        </form>
        <div class="fack">
            @if (Route::has('admin.password.request'))
            <a class="btn btn-link" style="margin-right: 8px" href="{{ route('admin.password.request') }}">
                <i class="fa fa-question-circle"></i>{{ __('Forgot Your Password?') }}
            </a>
            @endif
            <a href="{{url('/register')}}" style="margin-left: 4px; text-decoration: none">New User? <span
                    style="color: #007bff">Sign Up</span></a>
        </div>
    </div>

</body>

</html>
