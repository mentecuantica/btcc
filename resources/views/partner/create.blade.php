@extends('layouts.app')




@section('content')
    <div class="container">


    <h1>Add new partner</h1>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="binary-tree-chart" id="tree-container"></div>
                <hr/>
                <div class="panel panel-default">
                    <div class="panel-heading">Add partner </div>
                    <div class="panel-body">

                        {!! Form::model($newUser,['route' => 'partner.store']) !!}

                        {{ csrf_field() }}

                            @include('partner._form');


                        {!! Form::close() !!}
                        @include('_partials._errors')
                    </div>

                </div>
                <hr/>
            </div>
        </div>
    </div>
    </div>
@endsection
