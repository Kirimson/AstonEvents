@extends('layouts.app')
@section('pageName', 'Login')
@section('content')
    <h1>Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group row">
            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

            <div class="col-md-6">
                <input id="email" type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                       value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                                        <strong>{{ __("Email/Password not recognised. Please try again") }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                       required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                                        <strong>{{ __("Email/Password not recognised. Please try again") }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-outline-primary">
                    {{ __('Login') }}
                </button>
            </div>
        </div>
    </form>
    <div id="register-box">
        <h2>Want to become an Organiser?</h2>
        <div class="row mb-0">
            <a class="btn btn-outline-success col-lg-2 offset-5" href="{{ url('/register') }}" role="button">Create an
                Account</a>
        </div>
    </div>
@endsection
