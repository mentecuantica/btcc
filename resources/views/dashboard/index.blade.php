@extends('layouts.app')



@section('content')

{!! \Sentinel::getUser()->getUserLogin() !!}

@endsection