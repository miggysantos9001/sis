@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order - Create Order</h2>
@include('alert')
<a href="{{ route('distributions.create') }}" class="btn btn-back"><i class="fa fa-home"></i> Back to Build Order Products</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open(['method'=>'POST','action'=>'DistributionController@store_customer_order']) !!}
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Create Order</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Date</label>
                            {!! Form::date('date',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="">Reference #</label>
                            {!! Form::text('reference_number',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Terms</label>
                            {!! Form::select('terms',['CASH'=>'CASH','CHOD30'=>'CHOD30','CHOD15'=>'CHOD15','NET15'=>'NET15'],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Customer</label>
                            {!! Form::select('customer_id',$customers,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                        </div>
                        <div class="form-group">
                            <label>Representative</label>
                            {!! Form::text('representative',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="block block-themed">
            <div class="block-header bg-success">
                <h3 class="block-title">Product List</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed table-striped table-sm" style="text-transform: uppercase; font-size:10px;">
                            <thead> 
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Lot #</th>
                                    <th class="text-center">Expiry Date</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-center">Total Price</th>
                                    <th class="text-center">Final Price</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach (session('cart') as $products)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}
                                        {!! Form::hidden('purchase_order_item_id[]',$products['purchase_order_item_id']) !!}
                                        {!! Form::hidden('qty[]',$products['qty']) !!}
                                        {!! Form::hidden('discount[]',$products['discount']) !!}
                                    </td>
                                    <td class="text-center">{{ $products['product_name'] }}</td>
                                    <td class="text-center">{{ $products['qty'] }}</td>
                                    <td class="text-center">{{ $products['amount'] }}</td>
                                    <td class="text-center">{{ $products['lot_number'] }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($products['expiry_date'])->toFormattedDateString() }}</td>
                                    <td class="text-center">{{ number_format($products['discount'],2) }}</td>
                                    <td class="text-center">{{ number_format($products['qty'] * $products['amount'],2) }}</td>
                                    <td class="text-center">{{ number_format(($products['qty'] * $products['amount'] - $products['discount']),2) }}</td>
                                    <td class="text-center">
                                        <a href="#discount{{ $products['purchase_order_item_id'] }}" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></a>
                                    </td>
                                    @php
                                        $total += ($products['qty'] * $products['amount'] - $products['discount']);
                                    @endphp
                                </tr>    
                                @endforeach
                                <tr>
                                    <td colspan="8" class="text-right">Grand Total</td>
                                    <td class="text-center"><strong>{{ number_format($total,2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('modal')
@foreach (session('cart') as $products)
<div class="modal fade" id="discount{{ $products['purchase_order_item_id'] }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>['DistributionController@addtoDiscount',$products['purchase_order_item_id']]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Add Discount</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>Discount Price</label>
                        {!! Form::text('price','0.00',['class'=>'form-control']) !!}
                        {!! Form::text('purchase_order_item_id',$products['purchase_order_item_id'],['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> {{ __('msg.Update Entry') }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endforeach
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
                    data += '</tr>';
                    $("#prodtable tbody").append(data);
                
                });
            }
        });
    });

</script>
@endsection