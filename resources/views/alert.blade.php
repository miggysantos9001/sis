<style>
    .alert-danger {
        color: #fff;
        background-color: #f64747;
        border-color: #ec644b;
    }
</style>
@if(Session::has('flash_message'))
	<div class="alert alert-primary">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('flash_message') }}
    </div>
@endif

@if(Session::has('delete_message'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('delete_message') }}
    </div>
@endif

@if ($errors->any())
    <div style="margin-top:20px;" class="alert alert-danger">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul style="margin-top:10px;">
            @foreach ($errors->all() as $error)
                <li><span style="font-weight: bold;margin-top:10px;text-transform:uppercase;">{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
@endif