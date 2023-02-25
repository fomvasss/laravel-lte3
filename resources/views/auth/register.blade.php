@extends('lte3::auth.app')

@section('content')
<div class="card-body">
      <p class="login-box-msg">{{ trans('lte3::main.Register a new membership') }}</p>
        @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
      <form action="{{ url('register') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ trans('lte3::main.Name') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
            @error('name')
            <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
            <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group has-feedback @error('password') has-error @enderror">
            <input name="password" type="password" class="form-control" id="password" required placeholder="{{ trans('lte3::main.Password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @error('password')
                <span class="help-block">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="input-group mb-3 @error('password') has-error @enderror">
          <input name="password_confirmation" type="password" class="form-control" placeholder="{{ trans('lte3::main.Retype password') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="hidden" name="accept" value="0">
              <input name="accept" value="1" {{ old('accept') ? 'checked' : '' }} type="checkbox" id="agreeTerms">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">{{ trans('lte3::main.Register') }}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>

      <a href="/login" class="text-center">{{ trans('lte3::main.I already have a membership') }}</a>

    </div>
@endsection
