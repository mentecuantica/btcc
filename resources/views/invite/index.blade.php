@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1>{!! trans('Invite new user') !!}</h1>
            </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-md-6">

                    {!! Form::model($newInvite, ['url'=>['/invite/create']]) !!}

                    {!! Form::text('email',$newInvite->email, ['class' => 'form-control']) !!}
                    @if ($errors->has('email'))<p style="color:red;">{!!$errors->first('email')!!}</p>@endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::submit(trans('Send invite')) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

@endsection