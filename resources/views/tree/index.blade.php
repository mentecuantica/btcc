@extends('layouts.app')


@section('content')
    <div class="container">

        <ul>
            <li><a href="{!! url('/tree')  !!}" class="">Index</a></li>
            <li><a href="{!! url('/tree/linear')  !!}" class="">Linear</a></li>
            <li><a href="{!! url('/tree/binary',['id'=>Sentinel::getUser()->id])  !!}" class="">Binary</a></li>
            <li><a href="{!! url('/tree/binary')  !!}" class="">Binary all</a></li>
            <li><a href="{!! url('/tree/binary/json')  !!}" class="">Binary json</a></li>
            <li><a href="{!! url('/tree/binary/free',['id'=>user()->id])  !!}" class="">Free Binary (new method)</a></li>
            <li><a href="{!! url('/tree/ternary')  !!}" class="">Ternary</a></li>
        </ul>
    </div>



@endsection