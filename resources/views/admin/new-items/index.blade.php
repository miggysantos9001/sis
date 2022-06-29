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
                            <th class="text-center">PO #</th>
                            <th class="text-center">{{ __('msg.Branch Name') }}</th>
                            <th class="text-center">{{ __('msg.Supplier Name') }}</th>
                            <th class="text-center" width="50">{{ __('msg.Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
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
            {data: 'po_number', name: 'po_number'},
            {data: 'branch_id', name: 'branch_id'},
            {data: 'supplier_id', name: 'supplier_id'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection