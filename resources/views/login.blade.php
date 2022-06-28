@extends('log')
@section('content')
<form class="js-validation-signin px-30" action="{{ route('pasok') }}" method="post" id="myform">
	{{ csrf_field() }}
    <div class="form-group row">
        <div class="col-12">
            <div class="form-material floating">
                <input type="text" class="form-control input" id="login-username" name="name" value="">
                <label for="login-username">Username</label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-12">
            <div class="form-material floating">
                <input type="password" class="form-control input2" id="login-password" name="password" value="">
                <label for="login-password">Password</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary">
            <i class="si si-login mr-10"></i> Sign In
        </button>
    </div>
</form>
@endsection
@section('js')
<script>
    $(document).ready(function() {
       $('#myform').trigger("reset");
    });
</script>

@endsection