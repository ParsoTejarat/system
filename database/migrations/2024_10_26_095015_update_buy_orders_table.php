<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('customer_id');
            $table->string('invoice')->nullable()->after('description');
            $table->string('receipt')->nullable()->after('invoice');
            $table->string('seller')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buy_orders', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'invoice', 'receipt', 'seller']);
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
}
