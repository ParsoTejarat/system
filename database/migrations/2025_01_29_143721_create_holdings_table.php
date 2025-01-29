<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('commercial_code');
            $table->string('national_code');
            $table->string('national_id');
            $table->string('address');
            $table->string('zip_code');
            $table->string('phone_number1');
            $table->string('phone_number2')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('stamp')->nullable();
            $table->string('site_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('holdings');
    }
}
