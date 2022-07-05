@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Product Module') }} - {{ __('msg.View Product') }}</h2>
@include('alert')
<a href="{{ action('ProductController@index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.View Product Details') }}</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>{{ __('msg.Category') }}:</td>
                                    <td style="font-weight: bold;">{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('msg.Description') }}:</td>
                                    <td style="font-weight: bold;">{{ $product->description }}</td>
                                </tr>
                                <tr>
                                    <td>Quantity:</td>
                                    <td style="font-weight: bold;">{{ $product->qty }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>{{ __('msg.Product Price List History') }}</p>
                        <a href="#price{{ $product->id }}" data-toggle="modal" class="btn btn-primary btn-sm" style="margin-top:-10px;margin-bottom: 10px;">{{ __('msg.Create New Price List') }}</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ __('msg.Branch') }}</th>
                                    <th class="text-center">Cost</th>
                                    <th class="text-center">Distribution Price</th>
                                    <th class="text-center">Retail Price / Box</th>
                                    <th class="text-center">Retail Price / Piece</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $b)
                                <?php 
                                    $pricing = \App\Product_pricing::where('product_id',$product->id)
                                        ->where('branch_id',$b->id)
                                        ->orderBy('id','DESC')
                                        ->first();
                                    
                                ?>
                                <tr style="font-weight:bold;">
                                    <td>{{ $b->name }}</td>
                                    <td class="text-right">{{ ($pricing != NULL) ? number_format($pricing->stp,2) : '-' }}</td>
                                    <td class="text-right">{{ ($pricing != NULL) ? number_format($pricing->wsp,2) : '-' }}</td>
                                    <td class="text-right">{{ ($pricing != NULL) ? number_format($pricing->srp,2) : '-' }}</td>
                                    <td class="text-right">{{ ($pricing != NULL) ? number_format($pricing->srpp,2) : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="price{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>['ProductController@new_price',$product->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Create New Price') }} {{ $product->description }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ __('msg.Select Branch') }}</label>
                                {!! Form::select('branch_id',$branch,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cost</label>
                                {!! Form::text('stp','0.00',['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Distribution Price</label>
                                {!! Form::text('wsp','0.00',['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Retail Price / Box</label>
                                {!! Form::text('srp','0.00',['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Retail Price / Piece</label>
                                {!! Form::text('srpp','0.00',['class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Entry
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection