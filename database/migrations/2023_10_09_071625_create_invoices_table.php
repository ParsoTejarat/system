<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name');
            $table->unsignedInteger('economical_number')->comment('شماره اقتصادی');
            $table->unsignedInteger('national_number')->comment('شماره ملی');
            $table->string('province');
            $table->string('city');
            $table->text('address');
            $table->string('postal_code');
            $table->string('phone');
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
        Schema::dropIfExists('invoices');
    }
}
