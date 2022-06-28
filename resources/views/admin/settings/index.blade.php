@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Settings Module') }}</h2>
@include('alert')
<a href="#addBranch" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Settings List') }}</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('msg.Branch') }}</th>
                            <th class="text-center">{{ __('msg.Name') }}</th>
                            <th class="text-center">{{ __('msg.Contact Number') }}</th>
                            <th width="50" class="text-center">{{ __('msg.Action') }}</th>
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
            {!! Form::open(['method'=>'POST','action'=>'SettingController@store']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Create Setting</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>{{ __('msg.Select Branch') }}</label>
                        {!! Form::select('branch_id',$branches,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Name') }}</label>
                        {!! Form::text('name',null,['class'=>'form-control']) !!}
                    </div>                    
                    <div class="form-group">
                        <label>{{ __('msg.Address') }}</label>
                        {!! Form::text('address',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Contact Number') }}</label>
                        {!! Form::text('contact',null,['class'=>'form-control']) !!}
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
@foreach($settings as $data)
<div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['SettingController@update',$data->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Edit Settings') }} of {{ $data->name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>{{ __('msg.Select Branch') }}</label>
                        {!! Form::select('branch_id',$branches,$data->branch_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Name') }}</label>
                        {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
                    </div>                    
                    <div class="form-group">
                        <label>{{ __('msg.Address') }}</label>
                        {!! Form::text('address',$data->address,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Contact Number') }}</label>
                        {!! Form::text('contact',$data->contact,['class'=>'form-control']) !!}
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
        ajax: "{{ action('SettingController@index') }}",
        columns: [
            {data: 'branch_id', name: 'branch_id'},
            {data: 'name', name: 'name'},
            {data: 'contact', name: 'contact'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection