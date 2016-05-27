@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Please login</div>

                <div class="panel-body">
                    You need to <a href="{{ url('/login') }}">login</a> to perform any action
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
