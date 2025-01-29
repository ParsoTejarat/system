<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('holding_id')->nullable();
            $table->string('invoice_number');
            $table->string('customer_name');
            $table->string('commercial_code');
            $table->string('national_code')->nullable();
            $table->string('need_no');
            $table->string('zip_code');
            $table->string('phone_number');
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->text('description')->nullable();
            $table->longText('products')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('holding_id')->references('id')->on('holdings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_invoices');
    }
}
