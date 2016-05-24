@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">

                {!! Form::model($profile, ['route'=>['profile.update',$user->id]]) !!}

                {!! Form::text('name',$profile->name, ['class' => 'form-control']) !!}

                @if ($errors->has('name'))<p style="color:red;">{!!$errors->first('name')!!}</p>@endif

                {!! Form::text('surname', $profile->surname, ['class' => 'form-control']) !!}

                @if ($errors->has('surname'))<p style="color:red;">{!!$errors->first('surname')!!}</p>@endif
                {!! Form::submit('Сохранить профайл') !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection