@extends('master')

@section('content')
<h2 class="content-heading">Customer Module</h2>
@include('alert')
<a href="#addCustomer" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Customer List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">Company Name</th>
                            <th class="text-center">Contact Person</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Mobile</th>
                            <th class="text-center">TIN #</th>
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
<div class="modal fade" id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>'CustomerController@store']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Create Customer</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>Company Name</label>
                        {!! Form::text('company_name',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        {!! Form::text('contact_person',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        {!! Form::text('address',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        {!! Form::text('mobile',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>TIN #</label>
                        {!! Form::text('tin_number',null,['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> {{ __('msg.Save Entry') }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@foreach($data as $d)
<div class="modal fade" id="edit{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['CustomerController@update',$d->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Update Customer : {{ $d->company_name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>Company Name</label>
                        {!! Form::text('company_name',$d->company_name,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        {!! Form::text('contact_person',$d->contact_person,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        {!! Form::text('address',$d->address,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        {!! Form::text('mobile',$d->mobile,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>TIN #</label>
                        {!! Form::text('tin_number',$d->tin_number,['class'=>'form-control']) !!}
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
  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ action('CustomerController@index') }}",
        columns: [
            {data: 'company_name', name: 'company_name'},
            {data: 'contact_person', name: 'contact_person'},
            {data: 'address', name: 'address'},
            {data: 'mobile', name: 'mobile'},
            {data: 'tin_number', name: 'tin_number'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection