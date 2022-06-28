@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Inventory Module') }}</h2>
@include('alert')
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Daily Inventory Report') }}</h3>
            </div>
            <div class="block-content block-content-full">
                {!! Form::open(['method'=>'POST','action'=>'InventoryController@post_inventory']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('msg.Select Category') }}</label>
                            {!! Form::select('category_id',$categories,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT', 'style'=>'width:100%;']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('msg.Select Branch') }}</label>
                            {!! Form::select('branch_id',$branches,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT', 'style'=>'width:100%;']) !!}
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