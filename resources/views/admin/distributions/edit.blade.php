@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order - Update Order</h2>
@include('alert')
<a href="{{ route('distributions.index') }}" class="btn btn-back"><i class="fa fa-home"></i> Back to Index</a>
<div style="margin-bottom:20px;"></div>
{!! Form::model($distribution,['method'=>'PATCH','action'=>['DistributionController@update',$distribution->id]]) !!}
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Update Order</h3>
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
                                @foreach($distribution->distribution_items as $row)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $row->po_item->product->description }}</td>
                                    <td class="text-center">{{ $row->qty }}</td>
                                    <td class="text-center">{{ $row->po_item->product->pricing->wsp }}</td>
                                    <td class="text-center">{{ $row->po_item->lot_number }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->po_item->expiry_date)->toFormattedDateString() }}</td>
                                    <td class="text-center">{{ number_format($row->discount,2) }}</td>
                                    <td class="text-center">{{ number_format($row->qty * $row->po_item->product->pricing->wsp,2) }}</td>
                                    <td class="text-center">{{ number_format(($row->qty * $row->po_item->product->pricing->wsp) - $row->discount,2) }}</td>
                                    <td class="text-center">
                                        <a href="#edit{{ $row->id }}" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('distribution.delete-item',$row->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                    @php
                                        $total += ($row->qty * $row->po_item->product->pricing->wsp)- $row->discount;
                                    @endphp
                                </tr>    
                                @endforeach
                                <tr>
                                    <td colspan="8" class="text-right">Grand Total</td>
                                    <td colspan="2" class="text-center"><strong>{{ number_format($total,2) }}</strong></td>
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
@foreach($distribution->distribution_items as $row)
<div class="modal fade" id="edit{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>['DistributionController@update_entry',$row->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Edit Entry</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        {!! Form::label('Quantity') !!}
                        {!! Form::text('qty',$row->qty,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Discount') !!}
                        {!! Form::text('discount',$row->discount,['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endforeach
@endsection