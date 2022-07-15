<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id');
            $table->date('received_date');
            $table->string('reference_number');
            $table->string('received_by');
            $table->tinyInteger('isComplete')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_purchase_orders');
    }
}
