@extends('master')

@section('content')
<h2 class="content-heading">User Access Module</h2>
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">Create User</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>'UserController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('Username') !!}
                        {!! Form::text('name',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Password') !!}
                        {!! Form::password('password',['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Select Usertype:') !!}
                        {!! Form::select('usertype_id',$usertypes,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;   ']) !!}
                    </div>                
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">User Access List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Usertype</th>
                            <th class="text-center" width="120">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
@foreach($users as $data)
<div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'PATCH','action'=>['UserController@update',$data->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Edit {{ $data->name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="form-group">
                        {!! Form::label('User Name') !!}
                        {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Password') !!}
                        {!! Form::password('password',['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('','Select Usertype:') !!}
                        {!! Form::select('usertype_id',$usertypes,$data->usertype_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;   ']) !!}
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
<div class="modal fade" id="delete{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content modal-lg">
            <div class="block block-themed">
                <div class="block-header bg-danger">
                    <h3 class="block-title">Delete Entry</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <p class="text-center">Are you sure you want to delete this entry?</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ action('UserController@delete',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete Entry</a>
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
        ajax: "{{ action('UserController@index') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'usertype_id', name: 'usertype_id'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection