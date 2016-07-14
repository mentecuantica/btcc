@extends('layouts.app')

@section('content')
    <h3>{!! trans('Transaction List') !!}</h3>

    @if(count($transactions))

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
                <th>RECEIVER</th>
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{$t->id}}</td>
                    <td>{{$t->issuedBy->email}}</td>
                    <td>{{$t->getTypeText()}}</td>
                    <td>{{$t->status}}</td>
                    <td>{{$t->amount}}</td>
                    <td>{{$t->debit_flag}}</td>
                    <td>{{$t->credit_flag}}</td>
                    <td>{{$t->reciever->email}}</td>
                </tr>
                    @endforeach
            </tbody>
            </table>

    @else
        <div class="alert alert-info">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>You have no transactions</strong>
        </div>
    @endif
@stop