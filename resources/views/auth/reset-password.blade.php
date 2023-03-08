@extends('lte3::auth.app')

@section('content')
    <div class="card-body">
        <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
        {!! Lte3::formOpen(['action' => route('password.update'), 'method' => 'POST']) !!}
            {!! Lte3::hidden('token', request()->route('token')) !!}
            {!! Lte3::text('email', null, [
                'type' => 'email',
                'placeholder' => 'Email',
                'label' => '',
                'class_wrap' => 'mb-3',
                'append' => '<span class="fas fa-envelope"></span>',
            ]) !!}
            {!! Lte3::text('password', null, [
               'type' => 'password',
               'placeholder' => 'Password',
               'label' => '',
               'class_wrap' => 'mb-3',
               'append' => '<i class="fas fa-lock"></i>',
            ]) !!}
            {!! Lte3::text('password_confirmation', null, [
                'type' => 'password',
                'placeholder' => 'Password confirmation',
                'label' => '',
                'class_wrap' => 'mb-3',
                'append' => '<i class="fas fa-lock"></i>',
            ]) !!}

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Restore</button>
                </div>
            </div>
        {!! Lte3::formClose() !!}

        <p class="mt-3 mb-1">
            <a href="/login">Login</a>
        </p>
    </div>
@endsection
