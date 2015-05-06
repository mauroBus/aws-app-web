<div class="row">
  @foreach($images as $image)
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body" style="text-align:center;min-height: 120px;">
          <img src="{!! '../ImgUploads/'.$image->FileName !!}" alt="{!! $image->Description !!}" style="max-width:200px;max-height: 90px; ">
        </div>
        <div class="panel-footer">
          <div class="caption">
            <h5>{!! $image->OriginalName !!}</h5>
            <p>{!! $image->Description !!}</p>
            <p>
              @foreach($image->Tags as $tag)
                @if( $tag !== '' )
                  <span class="label label-primary">{!! $tag !!} <i class="glyphicon glyphicon-tag"></i></span>
                @endif
              @endforeach              
            </p>
            <p>
              <a href="{!! '../ImgUploads/'.$image->FileName !!}" class="btn btn-default" role="button" download="{!! $image->OriginalName !!}"><i class="glyphicon glyphicon-download-alt"></i></a>
              <a href="{!! route('images.delete', [$image->id]) !!}" class="btn btn-default" role="button" onclick="return confirm('Do you want to delete this image?')"><i class="glyphicon glyphicon-trash"></i></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>