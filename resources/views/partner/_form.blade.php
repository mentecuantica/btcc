<div class="row">
    <div class="form-group">
        <div class="col-sm-4">
            {!! Form::label('first_name') !!}
            {!! Form::text('first_name', NULL, ['required',
                      'class'=>'form-control',
                      'placeholder'=>'Your name']) !!}
        </div>
        <div class="col-sm-4">
            {!! Form::label('last_name') !!}
            {!! Form::text('last_name', NULL, ['required',
                      'class'=>'form-control',
                      'placeholder'=>'Surname pls']) !!}
        </div>

        <div class="col-sm-4">
            {!! Form::label('email',trans('Email')) !!}
            {!! Form::email('email', NULL,
                 ['required',
                       'class'=>'form-control',
                       ]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">

        {!! Form::label('package_id',trans('Package type')) !!}
        {!! Form::select('package_id', \Btcc\Models\Package::pluck('name','id'), ['required','type'=>'nubmer','class' => 'form-control']) !!}

    </div>
</div>


<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            {!! Form::label('country_id',trans('Country')) !!}
            {!! Form::select('country_id', \Btcc\Models\Country::pluck() , NULL , ['required','class' => 'form-control']) !!}

        </div>
        <div class="form-group">
            {!! Form::label('user_agreement',trans('User agreement')) !!}
            {!! Form::checkbox('user_agreement', 1, false,
                 ['required',
                       'class'=>'form-control',
                       ]) !!}

        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3">
            {!! Form::label('binary-parent-id',trans('Binary parent user id')) !!}
            {!! Form::text('binary-parent-id', NULL,
                 ['required',
                       'class'=>'form-control',
                       'type'=>'number'
                       ]) !!}
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            {!! Form::label('binary-position',trans('Binary pos')) !!}
            {!! Form::select('binary-position', ['L'=>'Left','R'=>'Right'], ['required','class' => 'form-control','id'=>'binary-position']) !!}

        </div>

    </div>

</div>


<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {{ Form::submit('Add partner') }}
    </div>
</div>