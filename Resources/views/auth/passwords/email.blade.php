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
                                    <h3 style="text-decoration: none !important; color: black">{{ env('APP_NAME')  }}</h3>
                                </a>
                                <h4 class="font-size-18 mt-4">Reset Password</h4>
                                <p class="text-muted">Reset your password to {{ env('APP_NAME')  }}.</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="mt-5">
                                <form class="form-horizontal form-signin" method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="form-group auth-form-group-custom mb-4">
                                        <i class="ri-mail-line auti-custom-input-icon"></i>
                                        <label for="inputEmail">Email</label>
                                        <input type="email" id="email"placeholder="Enter email" class="form-control mb-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="mt-4 text-center">
                                        <button type="submit" class="btn btn-primary w-50 pt-2 pb-2">Reset</button>
                                    </div>
                                    <div class="mt-4 text-center">
                                            <a class="reset-password-link text-muted text-medium" href="/admin" style="color: #5664d2 !important;">
                                                <span style="cursor: text; color: black !important;">Don't have an account?  </span>Log in</a>
                                    </div>
                                </form>
                            </div>
                            <div class="mt-5 text-center">
                                <p class="mb-3 text-center text-muted text-medium">
                                    &copy; {{ date('Y') }} {{ env('APP_NAME')  }} &middot; v{{ env('APP_VERSION') }}
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
<!--<div class="row">
    <div class="offset-4 col-md-4">
        <div class="page-section p-4 mt-10">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <h1 class="login-title">Reset your password</h1>

            <p class="text-muted">Fill and submit the form to receive a link to reset your password.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <input id="email" placeholder="Email address" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn w-100 btn-theme">Send Password Reset Link</button>
            </form>
        </div>
    </div>
</div>-->
@endsection
