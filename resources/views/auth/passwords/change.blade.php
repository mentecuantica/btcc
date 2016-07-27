@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('Change password') }}</div>

                <div class="panel-body">

                    {{ Form::open(['route' => 'account.password-change', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        {{ Form::label('old_password', trans('Old password'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-6">
                            {{ Form::input('password', 'old_password', null, ['class' => 'form-control', 'placeholder' => trans('Old password')]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('password', trans('New password'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-6">
                            {{ Form::input('password', 'password', null, ['class' => 'form-control', 'placeholder' => trans('New password')]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('password_confirmation', trans('New password confirm'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-6">
                            {{ Form::input('password', 'password_confirmation', null, ['class' => 'form-control', 'placeholder' => trans('New password confirm')]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            {{ Form::submit(trans('Update password'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-md-10 -->

    </div><!-- row -->
@endsection