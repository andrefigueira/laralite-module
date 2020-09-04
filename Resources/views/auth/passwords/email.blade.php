@extends('laralite::auth.layout')

@section('content')
    <div class="row">
        <div class="offset-4 col-md-4">
            <div class="page-section p-5">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <h1 class="text-center h3 mb-3 font-weight-normal">Reset your password</h1>

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
                    </div><!-- End form group -->

                    <button type="submit" class="btn w-100 btn-primary">Send Password Reset Link</button>
                </form>
            </div>
        </div><!-- End col -->
    </div><!-- End row -->
@endsection
