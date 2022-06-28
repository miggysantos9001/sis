@extends('master')

@section('content')
<h2 class="content-heading">{{ __('msg.Dashboard') }}</h2>
@if(Auth::user()->cashier_id != NULL)
    <?php 
        $checkSale = \App\Sale::where('cashier_id',$cashier)
            ->where('transaction_code',$transcode)
            ->first();
    ?>
    {!! Form::open(['method'=>'POST','action'=>'DashboardController@store_items']) !!}
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('msg.Transaction Code') }}:  {{ $transcode }}</h1>
        </div>
    	<div class="col-md-7">
    		<div class="form-group">
    			{!! Form::select('product_id',$products,null,['class'=>'form-control js-select2','placeholder'=>'ENTER PRODUCT NAME','style'=>'width:100%;']) !!}
    		</div>
    	</div>
        <div class="col-md-2">
            <div class="form-group">
                <div style="margin-top:10px;">
                <input type="checkbox" name="isWSP" value="1"><label style="margin-left:10px;">{{ __('msg.Wholesale Price') }}?</label>
                </div>
            </div>
        </div>
    	<div class="col-md-1">
    		<div class="form-group">
    			{!! Form::text('qty','1',['class'=>'form-control']) !!}
                {!! Form::hidden('transcode',$transcode,['class'=>'form-control']) !!}
                {!! Form::hidden('cashier_id',$cashier,['class'=>'form-control']) !!}
    		</div>
    	</div>
    	<div class="col-md-2">
    		<div class="form-group">
    			<button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('msg.Add Product') }}
                </button>
    		</div>
    	</div>
    </div>
    {!! Form::close() !!}
    @if($checkSale != NULL)
    {!! Form::open(['method'=>'POST','action'=>['DashboardController@save_transaction',$checkSale->id]]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Cashier POS</h3>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-bordered" style="text-transform: uppercase;">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('msg.Product Description') }}</th>
                                <th class="text-center">{{ __('msg.Quantity') }}</th>
                                <th class="text-center">{{ __('msg.Unit Price') }}</th>
                                <th class="text-center">{{ __('msg.Total Price') }}</th>
                                <th class="text-center" width="100">{{ __('msg.Action') }}</th>
                            </tr>
                        </thead>                    
                        <tbody>
                            @if($checkSale != NULL)
                            <?php $total = 0; ?>
                            @foreach($checkSale->saleitems as $data)
                            <tr>
                                <?php 
                                    $unit_price = \App\Product_pricing::where('product_id',$data->product_id)
                                        ->orderBy('id','DESC')
                                        ->first();

                                    $img = \App\Product_image::where('product_id',$data->product_id)
                                        ->first();

                                    if($img == NULL){
                                        $url= asset('public/images/noimage.png');
                                    }else{
                                        $url= asset('public/images/'.$img->name);
                                    }
                                ?>
                                <td>
                                    {{ $data->product->description }}<br>
                                    {{ $data->product->cdescription }}
                                </td>
                                <td class="text-right">{{ $data->qty }}</td>
                                <td class="text-right">{{ $data->unit_price }}</td>
                                <td class="text-right">{{ number_format($data->unit_price * $data->qty,2) }}</td>
                                <?php $total += $data->unit_price * $data->qty; ?>
                                <td class="text-center">
                                    <a href="{{ action('DashboardController@cancel_item',$data->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="2">{{ __('msg.Grand Total') }}</td>
                                <td class="text-right">{{ $checkSale->saleitems->sum('qty') }} items</td>
                                <td></td>
                                <td class="text-right" colspan="2">{{ number_format($total,2) }}</td>
                                {!! Form::hidden('total',$total,['class'=>'form-control','id'=>'total']) !!}
                                {!! Form::hidden('sale_id',$checkSale->id,['class'=>'form-control']) !!}
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        @if($checkSale != NULL)
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>LESS: {{ __('msg.Discount') }}</label><br>
                    <input type="radio" name="discount" value="0" id="discount"> {{ __('msg.Percentage') }} <br>
                    <input type="radio" name="discount" value="1" id="discount"> {{ __('msg.Pesos') }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>{{ __('msg.Discount Amount') }}</label>
                    {!! Form::text('discount_value','0.00',['class'=>'form-control','id'=>'discount_value']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('msg.Grand Total') }}</label>
                    {!! Form::text('',$total,['class'=>'form-control text-right','readonly'=>'readonly','id'=>'totald']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('msg.Amount Paid') }}</label>
                    {!! Form::text('paid','0.00',['class'=>'form-control text-right','id'=>'paid']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('msg.Change') }}</label>
                    {!! Form::text('change','0.00',['class'=>'form-control text-right','readonly'=>'readonly','id'=>'change']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="form-control btn btn-primary">
                    <i class="fa fa-save"></i> {{ __('msg.Save Transaction') }}
                </button>
            </div>
        </div>
        @endif
    @endif
    {!! Form::close() !!}
@else
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>{{ __('msg.Date') }}</td>
                    <td>{{ __('msg.Branch Name') }}</td>
                    <td>{{ __('msg.Sales') }}</td>
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $branch)
                <?php 
                    $sales = \App\Sale_transaction::where('date',\Carbon\Carbon::now()->toDateString())
                        ->where('branch_id',$branch->id)
                        ->sum('total');
                ?>
                <tr>
                    <td>{{ \Carbon\Carbon::now()->toFormattedDateString() }}</td>
                    <td>{{ $branch->name }}</td>
                    <td>PHP {{ number_format($sales,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
@section('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    
    $(document).ready(function() {
        $('#discount_value').keyup(function(){
            var discount =$('input[name="discount"]:checked').val();
            //alert(discount);
            var total =$('#total').val();
            if(discount == 0){
                var totald = total - (total * ($(this).val() / 100));   
            }else{
                var totald = total - $(this).val(); 
            }

            $("#totald").val(totald.toFixed(2));
            
        });

        $('#paid').keyup(function(){
            var total =$('#totald').val();
            var paid =$('#paid').val();
            var change = paid - total;

            $("#change").val(change.toFixed(2));
        });
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
@endsection