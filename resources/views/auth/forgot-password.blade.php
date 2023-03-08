@extends('lte3::auth.app')

@section('content')
<div class="card-body">
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    {!! Lte3::formOpen(['action' => route('password.email'), 'method' => 'POST']) !!}
    {!! Lte3::text('email', null, [
        'type' => 'email',
        'placeholder' => 'Email',
        'label' => '',
        'class_wrap' => 'mb-3',
        'append' => '<i class="fas fa-envelope"></i>',
    ]) !!}
    <div class="row">
        <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Request new password</button>
        </div>
    </div>
    {!! Lte3::formClose() !!}
    <p class="mt-3 mb-1">
    <a href="/login">Login</a>
    </p>
</div>
@endsection
