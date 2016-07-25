{!! Form::model($profile, ['route'=>['acoount.profile.update',$user->id]]) !!}


<div class="form-group">
    {{ Form::label('First Name')}}
    {{ Form::text('first_name', $user->first_name, ['class' => 'form-control']) }}
</div>


<div class="form-group">
    {{ Form::label('Last name')}}
    {{ Form::text('last_name', $user->last_name, ['class' => 'form-control']) }}
</div>


<div class="form-group">
    {{ Form::label('phone')}}
    {{ Form::text('phone', $profile->phone, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('Country')}}
    {{ Form::select('country_code', \Btcc\Utilities\Countries::getCountries(), $profile->country_code, ['class' => 'form-control']) }}
</div>



{!! Form::submit('Update profile') !!}

{!! Form::close() !!}