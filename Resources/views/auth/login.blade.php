@extends('laralite::auth.layout')

@section('content')
    <div class="row">
        <div class="offset-5 col-2">
            <div class="page-section p-4 mt-10">
                <form class="form-signin" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h1 class="text-center h3 mb-3 font-weight-normal">{{ env('APP_NAME') }}</h1>

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
                        <a class="d-block mt-2 text-center text-medium" href="{{ route('password.request') }}">Reset password</a>
                    @endif
                </form>
            </div><!-- End page section -->

            <p class="mt-2 mb-3 text-center text-muted text-small">&copy; {{ date('Y') }} Laralite &middot; v{{ env('APP_VERSION') }}</p>
        </div><!-- End col -->
    </div><!-- End row -->
@endsection
