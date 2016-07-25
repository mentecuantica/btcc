@extends('layouts.app')

@section('content')
    <div class="container">
    <h1>{!! trans('Withdraw to your personal wallet') !!}</h1>

    {!! Form::model($transaction,['url'=>'transaction/withdraw']) !!}

        <div class="form-group">
            {{ Form::label('To wallet')}}
            {{ Form::text('wallet', '', ['class' => 'form-control']) }}
        </div>


        <div class="form-group">
        {{ Form::label('Amount')}}
        {{ Form::text('amount', '', ['class' => 'form-control']) }}

    </div>


    <div class="form-group">
        {{ Form::label('Comment')}}
        {{ Form::text('comment', '', ['class' => 'form-control']) }}
    </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Withdraw money to</button>
        </div>
    </div>

    {!! Form::close() !!}
    </div>
@stop