@extends('layouts.app')

@push('scripts')
<script src="/static/vendor/raphael/raphael.min.js"></script>
<script src="/static/vendor/treant/treant.js"></script>
<script src="/static/vendor/lodash/lodash.js"></script>
<script src="/static/js/binary-tree-functions.js"></script>
@endpush

@push('stylesheets')
{{--<link rel="stylesheet" href="/static/vendor/treant/treant.css">
<link rel="stylesheet" href="/static/css/binary-tree.css">--}}
@endpush


@section('content')
    <script type="text/javascript">
        $(document).ready(function () {
            console.log('Itsready',window.Btcc);
            initBinaryTree("#tree-container",Btcc.parent,Btcc.childrenNodes);
        });
    </script>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="binary-tree-chart" id="tree-container">


                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Add partner</div>
                    <div class="panel-body">

                        {!! Form::open(['route' => 'partner.store']) !!}

                        {{ csrf_field() }}

                            @include('partner._form');


                        {!! Form::close() !!}
                        @include('_partials._errors')
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
