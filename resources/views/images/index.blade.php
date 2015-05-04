@extends('app')

@section('content')
  <div class="row">
    <div class="col-md-10  col-md-offset-1">
      @include('flash::message')
    </div>
  </div>
  <!-- Main content -->
  <div class="row">
    <div class="col-md-10  col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 >Images 
            <a class="btn btn-primary pull-right" href="{!! route('images.create') !!}">Upload a new Image</a>
          </h3>
        </div><!-- /.box-header -->
        <div class="panel-body">
          @if($images->isEmpty())
            <div class="well text-center">We didnt found images</div>
          @else
            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills pull-right">
                  <li role="presentation"><a href="Javascript:ToggleViewThumb();"><i class="glyphicon glyphicon-th"></i></a></li>
                  <li role="presentation"><a href="Javascript:ToggleViewList();"><i class="glyphicon glyphicon-th-list"></i></a></li>
                </ul>
              </div>
            </div>
          <div id="ThumbView" class="DivThumbView">
           @include('images.index_thumb')
          </div>
          <div id="ReportView" class="DivListView" style="display:none">
             @include('images.index_report')
          </div>
          @endif
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>
<script>
//Functions to Toggle beetwen Thumb and list views
function ToggleViewThumb() { 
  $( ".DivThumbView" ).show(); 
  $( ".DivListView" ).hide(); 
}

function ToggleViewList() { 
  $( ".DivListView" ).show(); 
  $( ".DivThumbView" ).hide(); 
}
</script>
@endsection