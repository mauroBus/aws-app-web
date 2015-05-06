<div class="panel-body">

    <!--- desc Field -->
    <div class="form-group {{ $errors->has('Description') ? 'has-error' : '' }} " >
        {!! Form::label('Description', 'Description:') !!}
        <div class="input-group">
            {!! Form::text('Description', null, ['class' => 'form-control']) !!}
            <div class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></div>
        </div><!-- /.input group -->
        {!! $errors->first('Description', '<span class="help-block">:message</span>') !!}
    </div>

    <!-- tags Field -->
    <div class="form-group {{ $errors->has('Tags') ? 'has-error' : '' }} ">
        {!! Form::label('Tags', 'Tags:') !!}
        <div class="input-group">
            {!! Form::textarea('Tags', null, ['class' => 'form-control']) !!}
            <div class="input-group-addon"><i class="glyphicon glyphicon-tags"></i></div>
        </div><!-- /.input group -->
     {!! $errors->first('Tags', '<span class="help-block">:message</span>') !!}
    </div>

    <!-- file Field -->
    <div class="form-group {{ $errors->has('File') ? 'has-error' : '' }} ">
        {!! Form::label('File', 'File:') !!}
        {!! Form::file('File', null, ['class' => 'form-control']) !!}
        {!! $errors->first('File', '<span class="help-block">:message</span>') !!}
    </div>

    <!-- Submit Field -->
    <div class="form-group">
        {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
    </div>
</div>