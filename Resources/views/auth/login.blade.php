@extends('laralite::auth.layout')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-12">
            <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                <div class="w-100">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="text-center">
                               <a href="/admin" class="logo">
                                    <img src="{{URL::asset('images/logo.png')}}" class="logoImage" style="max-width: 100%; width: 100px">
{{--                                    <h3 style="text-decoration: none !important; color: black">{{ config('app.name') ?? 'Admin' }} Portal</h3>--}}
                                </a>
                                <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                <p class="text-muted">Sign in to continue to {{ config('app.name') }}.</p>
                            </div>

                            <div class="mt-5">
                                <form class="form-horizontal form-signin" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group auth-form-group-custom mb-4">
                                        <i class="ri-mail-line auti-custom-input-icon"></i>
                                        <label for="inputEmail">Email</label>
                                        <input type="email" id="email"placeholder="Email address" class="form-control mb-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="form-group auth-form-group-custom mb-4">
                                        <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                        <label for="inputPassword">Password</label>
                                        <input type="password" id="password" placeholder="Password" class="form-control mb-2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="customControlInline" name="remember" value="1" class="custom-control-input">
                                        <label for="customControlInline" class="custom-control-label">Remember me</label>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <button type="submit" class="btn btn-primary w-50 pt-2 pb-2">Log In</button>
                                    </div>
                                    <div class="mt-4 text-center">
                                        @if (Route::has('password.request'))
                                            <a class="reset-password-link text-muted" href="{{ route('password.request') }}">
                                                <i class="ri-lock-2-fill" style="font-size: 18px"></i>
                                                Forgot your password?</a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="mt-5 text-center">
                                <p class="mb-3 text-center text-muted text-medium">
                                    &copy; {{ date('Y') }} {{ config('app.name')  }} &middot; v{{ config('app.version') }}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-0">
            <div class="authentication-bg">
                <div class="bg-overlay"></div>
            </div>
        </div>
    </div>
<!--    <div class="row">
        <div class="offset-md-4 col-12 col-md-4">
            <div class="page-section p-4 mt-10">
                <form class="form-signin" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1 class="login-title">Login to {{ config('app.name') }}</h1>

                    <label for="inputEmail" class="sr-only">Email address</label>
                    <input id="email" type="email" placeholder="Email address" class="form-control mb-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <label for="inputPassword" class="sr-only">Password</label>
                    <input id="password" type="password" placeholder="Password" class="form-control mb-2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <button type="submit" class="btn btn-theme btn-block">Login</button>

                    @if (Route::has('password.request'))
                        <a class="reset-password-link" href="{{ route('password.request') }}">Reset password</a>
                    @endif
                </form>
            </div>

            <p class="mt-2 mb-3 text-center text-muted text-small">&copy; {{ date('Y') }} Laralite &middot; v{{ config('app.version') }}</p>
        </div>
    </div>-->
@endsection
