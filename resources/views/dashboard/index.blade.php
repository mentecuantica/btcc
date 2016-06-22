@extends('layouts.app')



@section('content')

{!! \Auth::getUser()->email !!}

@endsection