@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order - Create Order</h2>
@include('alert')
<a href="{{ route('new-items.index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<a href="#view" data-toggle="modal" class="btn btn-success"><i class="fa fa-shopping-cart"></i> View Cart</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open(['method'=>'POST','action'=>'DistributionController@addtoCart']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Build Order Products</h3>
            </div>
            <div class="block-content">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Product</label>
                            {!! Form::select('product_id',$products,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;','id'=>'combo1']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed" id="prodtable" style="text-transform: uppercase;font-size: 11px;">
                            <thead>
                                <tr>
                                    <th class="text-center" width="50"><i class="fa fa-check"></i></th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Lot #</th>
                                    <th class="text-center">Expiry Date</th>
                                    <th class="text-center" width="50">Quantity</th>
                                    <th class="text-center" width="150">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-cart-plus"></i> Add to Cart</button>
                        @if(session('cart'))
                        <a href="{{ route('distribution.create-order') }}" class="btn btn-success" style="text-transform: none !important;"><i class="fa fa-plus-circle"></i> Create Customer Order</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('modal')
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-xl" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>'CategoryController@store']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">View Cart</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if(session('cart'))
                            <table class="table table-condensed table-striped table-sm" style="text-transform: uppercase;">
                                <thead> 
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Lot #</th>
                                        <th class="text-center">Expiry Date</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center" width="50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('cart') as $products)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $products['product_name'] }}</td>
                                        <td class="text-center">{{ $products['qty'] }}</td>
                                        <td class="text-center">{{ $products['amount'] }}</td>
                                        <td class="text-center">{{ $products['lot_number'] }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($products['expiry_date'])->toFormattedDateString() }}</td>
                                        <td class="text-center">{{ number_format($products['qty'] * $products['amount'],2) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('distribution.remove-to-cart',$products['purchase_order_item_id']) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>    
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <h3 class="text-center">EMPTY CART</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    var product_id = $('#combo1').val();
    
    $('#combo1').change(function() {
        var combobox3 = $(this).val(); 
        $.ajax({    //create an ajax request to load_page.php
            type: 'GET',
            url: "{{ action('DistributionController@loadproducts') }}",//php file url diri     
            dataType: "json",    
            data: { combobox3 : combobox3 },
            success: function(response){
                $("#prodtable tbody").html("");
                $.each(response,function(index,value){
                    data = '<tr>';
                    data += '<td align="center"><input type="checkbox" name="products['+index+'][purchase_order_item_id]" value="'+value.id+'" "class"="form-control" style="margin-top:5px;"></td>';
                    data += '<td>'+value.product.description+'</td>';
                    data += '<td>'+value.qty+'</td>';
                    data += '<td>'+value.lot_number+'</td>';
                    data += '<td>'+value.expiry_date+'</td>';
                    data += '<td><input type="text" name="products['+index+'][qty]" class="form-control form-control-sm" value="0" style="width:50px;text-align:center;" ></td>';
                    data += '<td align="center">'+value.product.pricing.wsp+'</td>';
                    data += '</tr>';
                    $("#prodtable tbody").append(data);
                
                });
            }
        });
    });

</script>
@endsection