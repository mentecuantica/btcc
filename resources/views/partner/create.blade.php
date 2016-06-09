@extends('layouts.app')

@push('scripts')
<script src="/static/vendor/raphael.js"></script>
<script src="/static/vendor/treant/treant.js"></script>
<script src="/static/vendor/underscore/underscore.js"></script>
<script src="/static/js/binary-tree-functions.js"></script>
@endpush

@push('stylesheets')
<link rel="stylesheet" href="/static/vendor/treant/treant.css">
<link rel="stylesheet" href="/static/css/binary-tree.css">
@endpush


@section('content')
    <script type="text/javascript">
        var binary_tree = {
            chart: {
                container: "#tree-container"
            },

            nodeStructure: {
                text: {
                    name: "You: " + "<?//=$parent->name;?>",
                    desc: "ID: " + "<?//=$parent->id;?>",

                },
                children: {!! $jsonNodes !!}

            },


        };
        $( document ).ready(function() {
            initBinaryTree(binary_tree);
        });
    </script>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="binary-tree-chart" id="tree-container">


                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Add partner</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('partner.store') }}">
                            {{ csrf_field() }}


                            <div class="form-group">

                                {!! Form::label('package_id',trans('Package type')) !!}
                                {!! Form::select('package_id', \Btcc\Models\Package::pluck('name','id'), ['class' => 'form-control']) !!}
                                @if ($errors->has('package_id'))<p
                                        style="color:red;">{!!$errors->first('package_id')!!}</p>@endif
                            </div>


                            <div class="form-group">

                                {!! Form::label('binary_position',trans('Binary pos')) !!}
                                {!! Form::select('binary_position', ['L'=>'Left','R'=>'Right'], ['class' => 'form-control']) !!}
                                @if ($errors->has('binary_position'))<p
                                        style="color:red;">{!!$errors->first('binary_position')!!}</p>@endif
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('n') }}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {{ Form::submit('Add partner') }}
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
