@extends('master')

@section('content')
<h2 class="content-heading">Purchase Order - Create PO</h2>
@include('alert')
<a href="{{ route('new-items.index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open(['method'=>'POST','action'=>'NewItemController@store']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Create Purchase Order</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Date</label>
                            {!! Form::date('date',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">PO #</label>
                            {!! Form::text('po_number',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Branch</label>
                            {!! Form::select('branch_id',$branches,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Supplier</label>
                            {!! Form::select('supplier_id',$suppliers,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="clearfix"></div>
                    <div id="wrap">
                        <div class="wrapp">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('','Product') !!}
                                        {!! Form::select('product_id[]',$products,null,['class'=>'form-control js-select2','placeholder'=>'-- Select One --']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {!! Form::label('','Unit of Measure') !!}
                                        {!! Form::select('uom_id[]',$units,null,['class'=>'form-control js-select2','placeholder'=>'-- Select One --']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {!! Form::label('','Lot #') !!}
                                        {!! Form::text('lot_number[]',null,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {!! Form::label('','Exp Date') !!}
                                        {!! Form::date('expiry_date[]',null,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        {!! Form::label('','Quantity') !!}
                                        {!! Form::text('qty[]',null,['class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button type="button" class="btn btn btn-info btn-xs mt-4" id="bot" style=""><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
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
        <?php $x = 0; ?>
        var x = 0;
        $('#bot').on('click',function(){
        <?php $x++; ?>
        x++;
        var data =  '<div class="clearfix"></div><div class="wrapp'+x+'" style="margin-top:15px;"><div class="row">';
            data += '<div class="clearfix"></div><div class="col-md-4"><div class="form-group">';
            data += '{!! Form::select("product_id[]",$products,null,["class"=>"form-control js-select2","placeholder"=>"-- Select One --"]) !!}';
            data += '</div></div>'; 

            data += '<div class="col-md-2"><div class="form-group">';
            data += '{!! Form::select("uom_id[]",$units,null,["class"=>"form-control js-select2","placeholder"=>"-- Select One --"]) !!}';
            data += '</div></div>';
            
            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="text" name="lot_number[]" class="form-control" />';
            data += '</div></div>';

            data += '<div class="col-md-2"><div class="form-group">';
            data += '<input type="date" name="expiry_date[]" class="form-control" />';
            data += '</div></div>';

            data += '<div class="col-md-1"><div class="form-group">';
            data += '<input type="text" name="qty[]" class="form-control" />';
            data += '</div></div>';           

            data += '<div class="col-md-1"><div class="form-group">';
            data += '<button type="button" class="btn btn btn-danger btn-xs minus" style="margin-top:2px;"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            data += '</div></div></div></div>';

            $('#wrap').append(data);
            $(".wrapp"+x+" .js-select2").select2();
        });

        $('#wrap').on('click','.minus',function(){
            var $a = $(this).parent('div').parent('div').parent('div').parent('div').attr('class');
            $('.'+$a).remove();
        });
    });
</script>
@endsection