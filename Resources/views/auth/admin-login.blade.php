@extends('laralite::auth.layout')

@section('content')
    <div class="row">
        <div class="offset-4 col-md-4">
            <div class="page-section pt-4">
                <form class="form-signin" method="POST" action="{{ url('admin/login') }}">
                    @csrf
                    <h1 class="text-center h3 mb-3 font-weight-bold">APEX</h1>

                    <label for="inputEmail" class="sr-only">Email address</label>
                    <input id="email" type="email" placeholder="Email address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <label for="inputPassword" class="sr-only">Password</label>
                    <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>

                    @if (Route::has('password.request'))
                        <div class="row">
                            <div class="col-md-12">
                                <a class="d-block mt-2 text-center" href="{{ route('password.request') }}">Reset password</a>
                            </div>
                        </div>
                    @endif

                    <p class="mt-5 mb-3 text-center text-muted">&copy; 2017-2018</p>
                </form>
            </div>
        </div><!-- End col -->
    </div><!-- End row -->
@endsection
