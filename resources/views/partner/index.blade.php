@extends('layouts.app')

@section('content')
    <h1>{!! trans('Your partners') !!}</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (count($partners)>0)


        <ul>
            @foreach($partners as $partner)
                <li><a href="{{ route('partner.show',['partner'=>$partner]) }}">{{ $partner->email }}</a></li>

            @endforeach
        </ul>


    {{--    {{ \Auth::user()::buildTree($partners) }}--}}

    @else
        <div class="alert alert-info">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<strong>You have no partners</strong>
        </div>
    @endif
@stop