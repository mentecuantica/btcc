@extends('layouts.app')



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