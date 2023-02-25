@extends('lte3::auth.app')

@section('content')
<div class="card-body">
      <p class="login-box-msg">{{ trans('lte3::main.You are only one step a way from your new password, recover your password now.') }}</p>
      <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
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
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{ trans('lte3::main.Restore') }}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="login.html">{{ trans('lte3::main.Login') }}</a>
      </p>
    </div>
@endsection
