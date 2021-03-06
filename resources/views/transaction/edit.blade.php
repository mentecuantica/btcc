@extends('layouts.app')

@section('content')
    <div class="container">
    <h1>{!! trans('Refund wallet') !!}</h1>

    {!! Form::model($transaction,['route'=>'transaction.store']) !!}

    <div class="form-group">
        {{ Form::label('Type')}}
        {{ Form::select('type', \Btcc\Models\UserTransaction::getTransactionTypesValues(), ['class' => 'form-control']) }}
    </div>


    <div class="form-group">
        {{ Form::label('Amount')}}
        {{ Form::text('amount', '', ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('Reciever')}}
        {{ Form::text('reciever', '', ['class' => 'form-control']) }}
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
            <button type="submit" class="btn btn-default">Add money</button>
        </div>
    </div>

    {!! Form::close() !!}
    </div>
@stop