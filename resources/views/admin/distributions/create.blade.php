@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order - Create Order</h2>
@include('alert')
<a href="{{ route('new-items.index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open([]) !!}
<div class="row">
    {{-- <div class="col-md-4">
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
                            {!! Form::select('terms',[],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
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
    </div> --}}
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-success">
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
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
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
                    data += '<td align="center"><input type="checkbox" name="products['+index+'][purchase_order_id]" value="'+value.id+'" "class"="form-control" style="margin-top:5px;"></td>';
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