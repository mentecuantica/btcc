@extends('layouts.app')

@section('content')
    <section id="page-content">
        <h1>{!! trans('Your partners') !!}</h1>


        <a href="{!! route('partner.create') !!}" class="btn btn-success">Add partner</a>


        <div class="panel panel-default">
            <div class="panel-body">
                Partners count: {{ $partnersCount }}
            </div>
        </div>

        @if (count($partners)>0)


            <ul>
                @foreach($partners as $partner)
                    <li><a href="{{ route('partner.show',['partner'=>$partner]) }}">{{ $partner->email }}</a></li>

                @endforeach
            </ul>


            {{--    {{ \Auth::user()::buildRigidArrayTreeStructureForTreant($partners) }}--}}

        @else
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>You have no partners</strong>
            </div>
        @endif
    </section>

@stop