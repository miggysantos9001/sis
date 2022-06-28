@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Branch Module') }}</h2>
@include('alert')
<a href="#addBranch" class="btn btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Branch List') }}</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('msg.Name') }}</th>
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
            {!! Form::open(['method'=>'POST','action'=>'BranchController@store']) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">{{ __('msg.Create Branch') }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        <label>{{ __('msg.Branch Name') }}</label>
                        {!! Form::text('name',null,['class'=>'form-control']) !!}
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
@foreach($branches as $data)
<div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['BranchController@update',$data->id]]) !!}
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
                        <label>{{ __('msg.Branch Name') }}</label>
                        {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
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
        ajax: "{{ action('BranchController@index') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection