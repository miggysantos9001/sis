<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_payments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('distribution_id');
            $table->string('receipt_number');
            $table->string('payment_type');
            $table->decimal('amount',12,2);
            $table->text('remarks');
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
        Schema::dropIfExists('distribution_payments');
    }
}
