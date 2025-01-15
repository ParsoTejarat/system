<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSumColumnsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('system_price');
            $table->dropColumn('partner_price_tehran');
            $table->dropColumn('partner_price_other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('system_price')->comment('قیمت سامانه');
            $table->unsignedBigInteger('partner_price_tehran')->comment('قیمت همکار (تهران)');
            $table->unsignedBigInteger('partner_price_other')->comment('قیمت همکار (شهرستان)');
        });
    }
}
