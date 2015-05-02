<table id="ImgTable" class="table table-bordered table-hover table-striped">
  <thead>
    <tr>
      <th>Img</th>
      <th>Desc</th>
      <th>FileName</th>
      <th>Tags</th>
      <th>Edit/Delete</th>
    </tr>
  </thead>
  <tbody>	
    @foreach($images as $image)
      <tr>
        <td><img src="{!! '../ImgUploads/'.$image->FileName !!}"></td>
        <td>{!! $image->Descripcion !!}</td>
        <td>{!! $image->FileName !!}</td>
        <td>{!! $image->Tags !!}</td>
        <td class="col-centered">
        <a href="{!! $image->FileName !!}" class="btn btn-default" role="button" download><i class="glyphicon glyphicon-download-alt"></i></a>
        <a href="{!! route('images.delete', [$image->id]) !!}" class="btn btn-default" role="button" onclick="return confirm('Do you want to delete this image?')"><i class="glyphicon glyphicon-trash"></i></a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>