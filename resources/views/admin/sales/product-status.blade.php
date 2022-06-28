@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Sales Module') }}</h2>
@include('alert')
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Product Status Report') }}</h3>
            </div>
            <div class="block-content block-content-full">
                {!! Form::open(['method'=>'POST','action'=>'SalesController@post_product_status']) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label( __('msg.Select Branch')) !!}
                            {!! Form::select('branch_id',$branches,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT', 'style'=>'width:100%;']) !!}
                        </div>
                    </div>
                	<div class="col-md-8">
                		<div class="form-group">
                			{!! Form::label( __('msg.Select Product')) !!}
                			{!! Form::select('product_id',$products,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT', 'style'=>'width:100%;']) !!}
                		</div>
                	</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label( __('msg.Date From')) !!}
                            <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_from" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label( __('msg.Date To')) !!}
                            <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_to" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i> {{ __('msg.Generate Report') }}</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection