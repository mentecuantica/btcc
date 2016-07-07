@extends('layouts.app')

@section('content')
    <h3>{!! trans('Transaction List') !!}</h3>

    @if($transactions->count())

        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>ISSUED BY</th>
                <th>TYPE</th>
                <th>STATUS</th>
                <th>AMOUNT</th>
                <th>DEBIT</th>
                <th>CREDIT</th>
                <th class="text-right">OPTIONS</th>
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{$t->id}}</td>
                    <td>{{$t->user_id}}</td>
                    <td>{{$t->name}}</td>
                    <td>{{$t->reciever->email}}</td>
                    <td>{{$t->age}}</td>
                    <td>{{$t->gender}}</td>
                    <td>{{$t->about}}</td>
                </tr>
                    @endforeach
            </tbody>
            </table>
            {!! $transactions->render() !!}
    @else
        <div class="alert alert-info">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>You have no transactions</strong>
        </div>
    @endif
@stop