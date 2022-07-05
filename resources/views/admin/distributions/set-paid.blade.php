@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order - Set Paid</h2>
@include('alert')
<a href="{{ route('distributions.index') }}" class="btn btn-back"><i class="fa fa-home"></i> Back to Index</a>
<div style="margin-bottom:20px;"></div>
{!! Form::open(['method'=>'POST','action'=>['DistributionController@post_set_paid',$distribution->id]]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Distribution Order Details</h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm" style="text-transform:uppercase;">
                            <tbody>
                                <tr>
                                    <td>Date</td>
                                    <td><span style="margin-left: 10px;">{{ \Carbon\Carbon::parse($distribution->date)->toFormattedDateString() }}</span></td>
                                </tr>
                                <tr>
                                    <td>Reference #</td>
                                    <td><span style="margin-left: 10px;">{{ $distribution->reference_number }}</span></td>
                                </tr>
                                <tr>
                                    <td>Terms</td>
                                    <td><span style="margin-left: 10px;">{{ $distribution->terms }}</span></td>
                                </tr>
                                <tr>
                                    <td>Customer</td>
                                    <td><span style="margin-left: 10px;">{{ $distribution->customer->company_name }}</span></td>
                                </tr>
                                <tr>
                                    <td>Representative</td>
                                    <td><span style="margin-left: 10px;">{{ $distribution->representative }}</span></td>
                                </tr>
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
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Set Payment</h3>
                @php
                    $checkPayment = \App\Distribution_payment::where('distribution_id',$distribution->id)
                        ->first();
                @endphp
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Date</label>
                            @if($checkPayment != NULL)
                            {!! Form::date('date',$checkPayment->date,['class'=>'form-control']) !!}
                            @else
                            {!! Form::date('date',null,['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Payment Receipt #</label>
                            @if($checkPayment != NULL)
                            {!! Form::text('receipt_number',$checkPayment->receipt_number,['class'=>'form-control']) !!}
                            @else
                            {!! Form::text('receipt_number',null,['class'=>'form-control']) !!}
                            @endif
                            {!! Form::hidden('distribution_id',$distribution->id,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Select Payment Type</label>
                            @if($checkPayment != NULL)
                            {!! Form::select('payment_type',['CASH'=>'CASH','CHEQUE'=>'CHEQUE'],$checkPayment->payment_type,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                            @else
                            {!! Form::select('payment_type',['CASH'=>'CASH','CHEQUE'=>'CHEQUE'],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Payment Amount</label>
                            @if($checkPayment != NULL)
                            {!! Form::text('amount',$checkPayment->amount,['class'=>'form-control']) !!}
                            @else
                            {!! Form::text('amount','0.00',['class'=>'form-control']) !!}
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            @if($checkPayment != NULL)
                            {!! Form::textarea('remarks',$checkPayment->remarks,['class'=>'form-control']) !!}
                            @else
                            {!! Form::textarea('remarks',null,['class'=>'form-control']) !!}
                            @endif
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
                                    <th class="text-center">Total Price</th>
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
                                    <td class="text-center">{{ number_format($row->qty * $row->po_item->product->pricing->wsp,2) }}</td>
                                    @php
                                        $total += $row->qty * $row->po_item->product->pricing->wsp;
                                    @endphp
                                </tr>    
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-right">Grand Total</td>
                                    <td class="text-center"><strong>{{ number_format($total,2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($distribution->isPaid == 0)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Entry</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
