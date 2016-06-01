@extends('layouts.app')

@push('scripts')
<script src="/static/vendor/raphael.js"></script>
<script src="/static/vendor/treant/treant.js"></script>
@endpush

@push('stylesheets')
<link rel="stylesheet" href="/static/vendor/treant/treant.css">
<link rel="stylesheet" href="/static/css/binary-tree.css">
@endpush


@section('content')
    <div class="container">
        <div class="row">
            <div class="binary-tree-chart" id="tree-container"></div>


            <script>

                var binary_tree_config = {
                    chart: {
                        container: "#tree-container"
                    },

                    nodeStructure: {
                        text: { name: "Parent node"},
                        children:  {!! $jsonNodes !!}

                    },


                };
                    new Treant(binary_tree_config);
            </script>
        </div>

    </div>

@endsection