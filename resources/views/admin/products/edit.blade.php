@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Product Module') }} - {{ __('msg.Update Product') }}</h2>
@include('alert')
<a href="{{ action('ProductController@index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
{!! Form::model($product,['method'=>'PATCH','action'=>['ProductController@update',$product->id],'novalidate' => 'novalidate','files' => 'true']) !!}
<?php 
    $pricing = \App\Product_pricing::where('product_id',$product->id)
        ->orderBy('id','DESC')
        ->first();
?>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Update Product Form') }}</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('msg.Product Barcode') }}</label>
                            {!! Form::text('barcode',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('msg.Select Category') }}</label>
                            {!! Form::select('category_id',$categories,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('msg.Product Description') }}</label>
                            {!! Form::text('description',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('msg.Chinese').' '.__('msg.Product Description') }}</label>
                            {!! Form::text('cdescription',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @foreach($product->images as $pimages)
                        <img class="img-avatar img-avatar-thumb" src="<?php echo asset('public/images/'.$pimages->name) ?>" alt="">
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('msg.Upload Images') }}</label>
                            <input type="file" class="form-control" name="images[]" placeholder="address" multiple>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('msg.Update Entry') }}</button>
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