@extends('master')

@section('content')
<h2 class="content-heading">Setup Module</h2>
<a href="#addBranch" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Setup List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('msg.Name') }}</th>
                            <th class="text-center">{{ __('msg.Description') }}</th>
                            <th class="text-center">{{ __('msg.Owner') }}</th>
                            <th class="text-center" width="120">{{ __('msg.Action') }}</th>
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
            {!! Form::open(['method'=>'POST','action'=>'SetupController@store','novalidate' => 'novalidate','files' => 'true']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Create Setup') }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="input-file-now">{{ __('msg.Upload Logo') }}</label>
                        <input type="file" id="input-file-now" class="" name="pic"/>
                    </div>
                    <div class="form-group">
                        {!! Form::label( __('msg.Name')) !!}
                        {!! Form::text('name',null,['class'=>'form-control']) !!}
                    </div>                 
                    <div class="form-group">
                        <label>{{ __('msg.Description') }}</label>
                        {!! Form::text('description',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Owner') }}</label>
                        {!! Form::text('owner',null,['class'=>'form-control']) !!}
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
@foreach($setups as $data)
<div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['SetupController@update',$data->id],'novalidate' => 'novalidate','files' => 'true']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Edit') }} {{ $data->name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label for="input-file-now">{{ __('msg.Upload Logo') }}</label>
                        <input type="file" id="input-file-now" class="" name="pic"/>
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Name') }}</label>
                        {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
                    </div>                    
                    <div class="form-group">
                        <label>{{ __('msg.Description') }}</label>
                        {!! Form::text('description',$data->description,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label>{{ __('msg.Owner') }}</label>
                        {!! Form::text('owner',$data->owner,['class'=>'form-control']) !!}
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
<div class="modal fade" id="delete{{ $data->id }}">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed">
                <div class="block-header bg-danger">
                    <h3 class="block-title">{{ __('msg.Delete Entry') }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <center>
                    <i class="fa fa-exclamation-triangle" style="font-size:48px;color:red"></i>
                </center>
                <h3 class="text-center">{{ __('msg.Are you sure you want to delete this entry?') }}</h3>
                <p class="text-center">{{ $data->name }} | {{ $data->description }} | {{ $data->owner }}</p>
            </div>
            <div class="modal-footer">
                <a href="{{ action('SetupController@delete',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> {{ __('msg.Delete Entry') }}</a>
            </div>
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
        ajax: "{{ action('SetupController@index') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'owner', name: 'owner'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection