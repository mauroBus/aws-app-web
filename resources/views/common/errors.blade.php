@if($errors->any())
	<div class="alert alert-danger alert-dismissible">
		<h4><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Error!</h4>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<ul>
	        @foreach($errors->all() as $error)
	            <li>{!! $error !!}</li>
	        @endforeach
	    </ul>
	</div>
@endif