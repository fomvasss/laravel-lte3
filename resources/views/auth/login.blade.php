@extends('lte3::auth.app')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Sign in to start your session</p>
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ url('login') }}" method="post">
    @csrf
    <div class="input-group mb-3">
        <input name="email"  value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email">
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-envelope"></span>
        </div>
        </div>
        @error('email')
        <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="input-group mb-3">
        <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
        </div>
        @error('password')
        <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="row">
        <div class="col-8">
        <div class="icheck-primary">
            <input name="remember" {{ old('remember') ? 'checked' : null }} value="1"  type="checkbox" id="remember">
            <label for="remember">
            Remember Me
            </label>
        </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
        <!-- /.col -->
    </div>
    </form>

    <div class="social-auth-links text-center mt-2 mb-3">
    <a href="#" class="btn btn-block btn-primary">
        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
    </a>
    <a href="#" class="btn btn-block btn-danger">
        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
    </a>
    </div>
    <!-- /.social-auth-links -->

    <p class="mb-1">
        <a href="/forgot-password">Restore password</a>
    </p>
    <p class="mb-0">
        <a href="/register" class="text-center">Register</a>
    </p>
</div>
@endsection
