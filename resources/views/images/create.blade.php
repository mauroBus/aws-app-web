@extends('app')

@section('content')
  <div class="row">
    <!-- left column -->
    <div class="col-md-8  col-md-offset-2">
      <!-- general form elements -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3>Upload Image</h3>
        </div><!-- /.box-header -->
        <div class="panel-body">        
        <!-- form start -->
          @include('common.errors')

          {!! Form::open(['route' => 'images.store','files'=>true]) !!}

          @include('images.fields')

          {!! Form::close() !!}
        </div>
      </div>
    </div><!--/.col (left) -->
  </div>   <!-- /.row -->			
@endsection
