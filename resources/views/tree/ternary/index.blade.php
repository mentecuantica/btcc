@extends('layouts.app')


@section('content')
<section id="ternary-tree">
    <div class="container-fluid">
        <h2>Ternary tree</h2>
        {{--<script type="text/javascript">
            $(document).ready(function () {
                //initTernaryTree("#tree-container",Btcc.parent,Btcc.childrenNodes);
            });
        </script>--}}

        <div class="binary-tree-chart" id="tree-container"></div>


    </div>

</section>

@endsection