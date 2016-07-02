@extends('layouts.app')


@section('content')
    <div class="container">
        <h2>Ternary tree</h2>
        <script type="text/javascript">
            $(document).ready(function () {
                console.log('Itsready',window.Btcc);
                initTernaryTree("#tree-container",Btcc.parent,Btcc.childrenNodes);
            });
        </script>

        <div class="binary-tree-chart" id="tree-container"></div>


    </div>

@endsection