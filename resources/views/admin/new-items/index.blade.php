@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.New Item Module') }}</h2>
@include('alert')
<a href="#addBranch" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.New Items List') }}</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('msg.Date') }}</th>
                            <th class="text-center">{{ __('msg.Branch Name') }}</th>
                            <th class="text-center">{{ __('msg.Supplier Name') }}</th>
                            <th class="text-center">{{ __('msg.Product Description') }}</th>
                            <th class="text-center">{{ __('msg.Quantity') }}</th>
                            <th class="text-center">{{ __('msg.Expiry Date') }}</th>
                            <th class="text-center" width="50">{{ __('msg.Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="addBranch" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>'NewItemController@store']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Create New Item') }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>{{ __('msg.Date') }}</label>
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Branch') }}</label>
                        {!! Form::select('branch_id',$branches,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Supplier') }}</label>
                        {!! Form::select('supplier_id',$suppliers,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Product') }}</label>
                        {!! Form::select('product_id',$products,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>Select Unit of Measure</label>
                        {!! Form::select('unit_id',$units,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>Lot #</label>
                        {!! Form::text('lot_number',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Quantity') }}</label>
                        {!! Form::text('qty','0',['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Expiry Date') }}</label>
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="expiry_date" value="{{ \Carbon\Carbon::now()->toDateString() }}">
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
@foreach($newitems as $data)
<div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['NewItemController@update',$data->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Edit') }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>{{ __('msg.Date') }}</label>
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" value="{{ $data->date }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Branch') }}</label>
                        {!! Form::select('branch_id',$branches,$data->branch_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Supplier') }}</label>
                        {!! Form::select('supplier_id',$suppliers,$data->supplier_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Select Product') }}</label>
                        {!! Form::select('product_id',$products,$data->product_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>Select Unit of Measure</label>
                        {!! Form::select('unit_id',$units,$data->unit_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>Lot #</label>
                        {!! Form::text('lot_number',$data->lot_number,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Quantity') }}</label>
                        {!! Form::text('qty',$data->qty,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Expiry Date') }}</label>
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="expiry_date" value="{{ $data->expiry_date }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Update Entry
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
  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ action('NewItemController@index') }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'branch_id', name: 'branch_id'},
            {data: 'supplier_id', name: 'supplier_id'},
            {data: 'product_id', name: 'product_id'},
            {data: 'qty', name: 'qty'},
            {data: 'expiry_date', name: 'expiry_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection