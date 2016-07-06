{!! Form::open(['url'=>'/login']) !!}


{{--
<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
--}}
{{ Form::token() }}

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">E-Mail Address</label>

    <div class="col-md-6">
        {{--
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
        --}}
        {{ Form::email('email', 'admin@btcc.vgt' , ['class' => 'form-control']) }}
        @if ($errors->has('email'))
            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Password - 123456</label>

    <div class="col-md-6">


        {{ Form::password('password', ['class' => 'form-control']) }}
        {{--            <input type="password" class="form-control" name="password">

                    --}}@if ($errors->has('password'))
            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        <div class="checkbox">
            <label>
                {{ Form::checkbox('remember') }}
                Remember Me
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {{ Form::submit('Login') }}{{--
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-btn fa-sign-in"></i>Login
            </button>--}}

        <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
    </div>
</div>
{!! Form::close() !!}