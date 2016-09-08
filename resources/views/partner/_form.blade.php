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
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            {!! Form::label('country_code',trans('Country')) !!}
            {!! Form::select('country_code', \Btcc\Utilities\Countries::getCountries() , NULL , ['required','class' => 'form-control']) !!}

        </div>

    </div>
    <div class="form-group">
        <div class="col-sm-6">
            {!! Form::label('binary-parent-id',trans('parentid')) !!}
            {!! Form::text('binary-parent-id', NULL,
                 ['required',

                       'type'=>'number'
                       ]) !!}
            {!! Form::label('binary-position',trans('pos')) !!}
            {!! Form::select('binary-position', ['L'=>'Left','R'=>'Right'], ['required', 'id'=>'binary-position']) !!}
        </div>


    </div>

</div>

<hr/>

<div class="alert alert-success">
    <p>Your balance is: {{ $userInfo['wallet']['balance'] or '0.0' }} $</p>
</div>

<div class="row">
    <h3>Select package</h3>
    <div class="subscription-packages-group">
        @forelse($packages as $package)
            <div class="subscription-package">
                <div class="package-description">
                    <h4>{{$package->name}}</h4>
                    @foreach($package->features as $feature)
                        <p>{{$feature}}</p>
                    @endforeach
                    <div class="price"> Price: {{$package->price}}</div>


                    {{Form::radio('package_id',$package->id )}}
                </div>

            </div>
        @empty
            <p>No Packages</p>
        @endforelse

    </div>

    <hr/>




</div>
<div class="row">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <div class="form-group">
            <a href="#">Read User Agreement</a>
            {!! Form::label('user_agreement',trans('User agreement')) !!}
            {!! Form::checkbox('user_agreement', 1, false,
            ['required',

            ]) !!}

        </div>
    </div>

</div>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-8 col-sm-2">
            <a href="#" class="btn btn-primary">Cancel</a>

        </div>  <div class="col-sm-2">
            <button type="submit" class="btn btn-danger">Add partner</button>

        </div>
    </div>

</div>

