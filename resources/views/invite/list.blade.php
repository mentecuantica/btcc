@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1>{!! trans('Your sent invites') !!}</h1>
            </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-md-6">

                    <ul>
                        @foreach($invites as $invite)
    <li> {{ $invite->id }},  {{ $invite->email }}, {{ $invite->status }}</li>

                        @endforeach

                    </ul>

                </div>
            </div>
        </div>

@endsection