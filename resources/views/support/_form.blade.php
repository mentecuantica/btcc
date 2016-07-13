<div class="form-group">
    <legend>Create ticket</legend>
</div>
<div class="form-group">
    {{ Form::label('subject', 'Subject', ['class' => 'control-label']) }}
    <div class="col-sm-10">
        {{ Form::text('subject', '', ['class' => 'form-control','required'=>true]) }}

    </div>
</div>
<div class="form-group">
    {{ Form::label('message', 'Message', ['class' => 'control-label']) }}
    <div class="col-sm-10">
        {{ Form::textarea('message', '', ['class' => 'form-control','placeholder'=>'Message']) }}

    </div>
</div>





<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</div>