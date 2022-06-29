@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Product Module') }} - {{ __('msg.Create Product') }}</h2>
@include('alert')
<a href="{{ action('ProductController@index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open(['method'=>'POST','action'=>'ProductController@store','novalidate' => 'novalidate','files' => 'true']) !!}
<div class="row">
    <div class="col-md-8">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Create Product Form') }}</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('msg.Product Barcode') }}</label>
                            {!! Form::text('barcode',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.Select Branch') }}</label>
                            {!! Form::select('branch_id',$branches,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.Select Category') }}</label>
                            {!! Form::select('category_id',$categories,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.Product Description') }}</label>
                            {!! Form::text('description',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.Store Price') }}</label>
                            {!! Form::text('stp','0.00',['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.SRP') }}</label>
                            {!! Form::text('srp','0.00',['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>{{ __('msg.Wholesale Price') }}</label>
                            {!! Form::text('wsp','0.00',['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('msg.Upload Images') }}</label>
                            <input type="file" class="form-control" name="images[]" placeholder="address" multiple>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('msg.Save Entry') }}</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection