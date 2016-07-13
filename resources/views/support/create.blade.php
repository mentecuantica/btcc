@extends('layouts.app')


@section('content')
 <div class="container">

  <div class="row">
   {{ Form::model($ticket, ['route' => ['supportTicket.store'], 'method' => 'post']) }}

   {{ Form::token() }}
   @include('support._form');
   {{ Form::close() }}
  </div>
 
 </div>

@endsection