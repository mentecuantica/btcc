@extends('layouts.app')


@section('content')
    <div class="container">

        <ul>
            <li><a href="{!! url('/tree/linear/index')  !!}" class="">Linear</a></li>
            <li><a href="{!! url('/tree/binary/index')  !!}" class="">Binary</a></li>
            <li><a href="{!! url('/tree/ternary/index')  !!}" class="">Ternary</a></li>
            {{--<li><a href="{!! url('/tree/binary',['id'=>Auth::getUser()->id])  !!}" class="">Binary</a></li>--}}
            {{--     <li><a href="{!! url('/tree/ternary')  !!}" class="">Ternary</a></li>
                 <li><a href="{!! url('/tree/binary')  !!}" class="">Binary all</a></li>
                 <li><a href="{!! url('/tree/binary/json')  !!}" class="">Binary json</a></li>
                 <li><a href="{!! url('/tree/binary/free',['id'=>user()->id])  !!}" class="">Free Binary (new method)</a></li>--}}
        </ul>
    </div>



@endsection