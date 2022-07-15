@extends('master')
@section('content')
    <h2 class="content-heading">Purchase Order - Receive PO</h2>
    @include('alert')
    <a href="{{ route('new-items.index') }}" class="btn btn-back"><i class="fa fa-home"></i> {{ __('msg.Back to Main') }}</a>
    <div style="margin-bottom:20px;"></div>
    {!! Form::open(['method'=>'POST','action'=>['NewItemController@post_receive_entry',$po->id]]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">PO Details</h3>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Date: </td>
                                        <td>{{ \Carbon\Carbon::parse($po->date)->toFormattedDateString() }}</td>
                                    </tr>
                                    <tr>
                                        <td>PO #: </td>
                                        <td>{{ $po->po_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch: </td>
                                        <td>{{ $po->branch->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Supplier: </td>
                                        <td>{{ $po->supplier->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    @php
                        $checkRR = \App\Receive_purchase_order::where('purchase_order_id',$po->id)->first();
                    @endphp
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Date Received') !!}
                                @if($checkRR != NULL)
                                {!! Form::date('received_date',$checkRR->received_date,['class'=>'form-control']) !!}
                                @else
                                {!! Form::date('received_date',null,['class'=>'form-control']) !!}
                                @endif
                                {!! Form::hidden('purchase_order_id',$po->id,['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Reference #') !!}
                                @if($checkRR != NULL)
                                {!! Form::text('reference_number',$checkRR->reference_number,['class'=>'form-control']) !!}
                                @else
                                {!! Form::text('reference_number',null,['class'=>'form-control']) !!}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Received By') !!}
                                @if($checkRR != NULL)
                                {!! Form::text('received_by',$checkRR->received_by,['class'=>'form-control']) !!}
                                @else
                                {!! Form::text('received_by',null,['class'=>'form-control']) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Receive PO Items</h3>
                </div>
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center"><i class="fa fa-check"></i></th>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">UoM</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Lot #</th>
                                        <th class="text-center">Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($po->po_items as $row)
                                    @php
                                        $checkRRPO = \App\Receive_po_item::where('purchase_order_item_id',$row->id)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($checkRRPO != NULL)
                                            <input type="checkbox" name="purchase_order_item_id[]" value="{{ $row->id }}" checked>
                                            @else
                                            <input type="checkbox" name="purchase_order_item_id[]" value="{{ $row->id }}">
                                            @endif
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->product->description }}</td>
                                        <td>{{ $row->uom->name }}</td>
                                        <td>{{ $row->qty }}</td>
                                        <td>
                                            {!! Form::text('lot_number[]',$row->lot_number,['class'=>'form-control']) !!}
                                        </td>
                                        <td>
                                            {!! Form::date('expiry_date[]',$row->expiry_date,['class'=>'form-control']) !!}
                                        </td>
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
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('msg.Save Entry') }}</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection