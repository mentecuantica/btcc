{!! Form::model($profile, ['route'=>['account.profile.update',$user->id]]) !!}


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

@foreach($profile->additional_info as $key=>$value)
<div class="form-group">
    {{ Form::label($key)}}
    {{ Form::text('additional_info['.$key.']' , $value, ['class' => 'form-control']) }}
</div>
@endforeach




{!! Form::submit('Update profile') !!}

{!! Form::close() !!}