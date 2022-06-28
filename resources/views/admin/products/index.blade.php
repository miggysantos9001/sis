@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Product Module') }}</h2>
@include('alert')
<a href="{{ action('ProductController@create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('msg.Create Entry') }}</a>
<div style="margin-bottom:20px;"></div>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-primary">
                <h3 class="block-title">{{ __('msg.Product List') }}</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('msg.Image') }}</th>
                            <!-- <th class="text-center">{{ __('msg.Barcode Number') }}</th> -->
                            <th class="text-center">{{ __('msg.Category') }}</th>
                            <th class="text-center">{{ __('msg.Description') }}</th>
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
        ajax: "{{ action('ProductController@index') }}",
        columns: [
            {data: 'img', name: 'img'},
            // {data: 'barcode', name: 'barcode'},
            {data: 'category_id', name: 'category_id'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection