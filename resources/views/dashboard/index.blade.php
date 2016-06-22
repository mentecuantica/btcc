@extends('layouts.app')



@section('content')

{!! \Auth::getUser()->getUserLogin() !!}

@endsection