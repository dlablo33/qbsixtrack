@if ($message = Session::get('success'))
<div style="color:green;">	
        <strong>{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('error'))
<div style="color:red;">	
        <strong>{{ $message }}</strong>
</div>
@endif



@if ($errors->any())
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	Please check the form below for errors
</div>
@endif