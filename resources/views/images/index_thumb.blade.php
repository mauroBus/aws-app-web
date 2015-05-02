<div class="row">
  @foreach($images as $image)
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body" style="text-align:center">
          <img src="{!! '../ImgUploads/'.$image->FileName !!}" alt="{!! $image->Descripcion !!}" style="height: 90px; ">
        </div>
        <div class="panel-footer">
          <div class="caption">
            <h5>{!! $image->FileName !!}</h5>
            <p>{!! $image->Descripcion !!}</p>
            <p>
              <a href="{!! $image->FileName !!}" class="btn btn-default" role="button" download><i class="glyphicon glyphicon-download-alt"></i></a>
              <a href="{!! route('images.delete', [$image->id]) !!}" class="btn btn-default" role="button" onclick="return confirm('Do you want to delete this image?')"><i class="glyphicon glyphicon-trash"></i></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>