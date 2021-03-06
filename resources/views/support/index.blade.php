@extends('layouts.app')


@section('content')
    <div class="container">

        @if (count($tickets)>0)
            <h2>Your support tickets list</h2>

            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>status</th>
                    <th>subject</th>
                    <th>create_date</th>

                </tr>
                </thead>

                <tbody>
                @foreach($tickets as $t)
                    <tr>
                        <td>{{$t->id}}</td>
                        <td>{{$t->status}}</td>
                        <td>{{$t->subject}}</td>
                        <td>{{$t->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @else

            <h2>You have no tickets</h2>


        @endif

            <div class="alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong><a href="{{ route('supportTicket.create') }}">Create!</a></strong>
            </div>
    </div>

@endsection