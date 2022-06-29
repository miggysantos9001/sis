@extends('master')

@section('content')
<h2 class="content-heading">Purchase Order - Update PO</h2>
@include('alert')
<a href="{{ route('new-items.index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
<div style="margin-bottom:20px;"></div>
{!! Form::model($po,['method'=>'PATCH','action'=>['NewItemController@update',$po->id]]) !!}
<div class="row mb-3">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Purchased Items</h3>
            </div>
            <div class="block-content">
                <table class="table table-condensed table-bordered table-sm" style="font-size:10px;text-transform:uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center">Lot #</th>
                            <th class="text-center">Expiry Date</th>
                            <th class="text-center" width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->po_items as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->qty }}</td>
                            <td>{{ $row->product->description }}</td>
                            <td>{{ $row->uom->name }}</td>
                            <td>{{ $row->lot_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->expiry_date)->toFormattedDateString() }}</td>
                            <td class="text-center">
                                <a href="#edit{{ $row->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('new-item.delete-item',$row->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Update Purchase Order</h3>
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
@section('modal')
@foreach($po->po_items as $row)
<div class="modal fade" id="edit{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content modal-lg">
            {!! Form::open(['method'=>'POST','action'=>['NewItemController@update_item',$row->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Update Item</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('','Product') !!}
                            {!! Form::select('product_id',$products,$row->product_id,['class'=>'form-control js-select2','placeholder'=>'-- Select One --','style'=>'width:100%;']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('','Unit of Measure') !!}
                            {!! Form::select('uom_id',$units,$row->uom_id,['class'=>'form-control js-select2','placeholder'=>'-- Select One --','style'=>'width:100%;']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('','Lot #') !!}
                            {!! Form::text('lot_number',$row->lot_number,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('','Exp Date') !!}
                            {!! Form::date('expiry_date',$row->expiry_date,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('','Qty') !!}
                            {!! Form::text('qty',$row->qty,['class'=>'form-control']) !!}
                        </div>
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