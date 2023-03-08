@extends('lte3::auth.app')

@section('content')
    <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>
        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        {!! Lte3::formOpen(['action' => url('register'), 'method' => 'POST']) !!}
            {!! Lte3::text('name', null, [
                'placeholder' => 'Name',
                'label' => '',
                'class_wrap' => 'mb-3',
                'append' => '<span class="fas fa-user"></span>',
            ]) !!}

            {!! Lte3::text('email', null, [
                'type' => 'email',
                'placeholder' => 'Email',
                'label' => '',
                'class_wrap' => 'mb-3',
                'append' => '<i class="fas fa-envelope"></i>',
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
                <div class="col-8">
                    {!! Lte3::checkbox('accept', null, [
                        'label' => 'I agree to the <a href="#">terms</a>',
                        'class_wrap' => 'icheck-primary',
                    ]) !!}
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div>
            </div>
        {!! Lte3::formClose() !!}

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

        <a href="/login" class="text-center">I already have a membership</a>

    </div>
@endsection
