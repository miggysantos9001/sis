@extends('master')

@section('content')
<h2 class="content-heading">Distribution Order</h2>
@include('alert')
<a href="{{ route('distributions.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Order List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter" style="text-transform: uppercase;font-size:11px;" id="table1">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('msg.Date') }}</th>
                            <th class="text-center">Reference #</th>
                            <th class="text-center">Terms</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Representative</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center" width="50">{{ __('msg.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach($distributions as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }}</td>
                            <td>{{ $data->reference_number }}</td>
                            <td>{{ $data->terms }}</td>
                            <td>{{ $data->customer->company_name }}</td>
                            <td>{{ $data->representative }}</td>
                            <td>{{ ($data->isPaid == 0) ? 'OPEN' : 'PAID' }}</td>
                            <td>
                                @foreach($data->distribution_items as $row)
                                @php
                                    $total += $row->qty * $row->po_item->product->pricing->wsp;
                                @endphp
                                @endforeach
                                {{ number_format($total,2) }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" id="btnGroupDrop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i></button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                        @if($data->isPaid == 0)
                                        <a href="{{ route('distributions.edit',$data->id) }}" class="dropdown-item">Edit Entry</a>
                                        <a href="{{ route('distribution.delete',$data->id) }}" class="dropdown-item">Delete Entry</a>
                                        <a href="{{ route('distribution.set-paid',$data->id) }}" class="dropdown-item">Set Paid</a>
                                        @endif
                                        <a href="{{ route('distribution.set-paid',$data->id) }}" class="dropdown-item">View Payment</a>
                                        <a href="{{ route('distribution.print',$data->id) }}" class="dropdown-item">Print Payment</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

@endsection