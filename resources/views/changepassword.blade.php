@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Change Password Module') }}</h2>
<div class="row">
    <div class="col-md-6">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.User Account of') }} {{ $user->name }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>['DashboardController@post_changepassword',$user->id]]) !!}    
                    <div class="form-group">
                        <label>{{ __('msg.Username') }}</label>
                        {!! Form::text('username',$user->name,['class'=>'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.New Password') }}</label>
                        {!! Form::password('password',['class'=>'form-control']) !!}
                    </div>                
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('msg.Update Entry') }}</button>
                        @if($user->role == 'STUDENT')
                        <a href="{{ action('StudentController@dashboard') }}" class="btn btn-success"><i class="fa fa-home"></i> Back to Dashboard</a>
                        @endif
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection