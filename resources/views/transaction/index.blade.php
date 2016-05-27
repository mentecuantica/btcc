@extends('layouts.app')

@section('content')
    <h1>{!! trans('Transaction List') !!}</h1>


    @if (count($transactions)>0)


        <ul>
            @foreach($transactions as $transaction)
                <li>{{ $transaction->recieverUser->email }}</li>

            @endforeach
        </ul>

    @else
        <div class="alert alert-info">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>You have no transactions</strong>
        </div>
    @endif
@stop