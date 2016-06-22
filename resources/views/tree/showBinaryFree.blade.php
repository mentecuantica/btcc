@extends('layouts.app')

@push('scripts')
<script src="/static/vendor/raphael.js"></script>
<script src="/static/vendor/treant/treant.js"></script>
<script src="/static/vendor/underscore/underscore.js"></script>
<script src="/static/js/binary-tree-functions.js"></script>
@endpush

@push('stylesheets')
<link rel="stylesheet" href="/static/vendor/treant/treant.css">
@endpush


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <script type="text/javascript">

                    $(document).ready(function () {
                        console.log('Itsready',window.Btcc);
                        initBinaryTree("#tree-container",Btcc.parent,Btcc.childrenNodes);
                    });
                </script>

                <div class="binary-tree-chart" id="tree-container">
            </div>

        </div>

    </div>

@endsection