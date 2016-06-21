@extends('layouts.landing')


@section('content')
    <div class="container">
    	<div class="row">
            <div class="jumbotron">
            	<div class="container">
            		<h1>Hello, btcc!</h1>
            		<p>Landing page...</p>
            		<p>
            			<a class="btn btn-primary btn-lg">Learn more</a>
            		</p>
            	</div>
            </div>
    	</div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        @include('auth._loginForm')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection