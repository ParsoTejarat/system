<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGuaranteeTable extends Migration
{
    public function up()
    {
        Schema::table('guarantees', function (Blueprint $table) {

            $table->dropColumn([
                'serial',
                'period',
                'status',
                'activated_at',
                'expired_at',
                'created_at',
                'updated_at'
            ]);
        });

        Schema::table('guarantees', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();

            $table->string('serial_number')->nullable()->unique();
            $table->string('tracking_code')->nullable();
            $table->string('product_identifier')->nullable();
            $table->string('status')->nullable();
            $table->integer('period')->nullable();
            $table->string('importing_company')->nullable();

            $table->timestamp('start_time');
            $table->timestamp('expire_time')->nullable();

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guarantees', function (Blueprint $table) {
            $table->string('serial')->unique();
            $table->enum('period', ['12', '24']);
            $table->enum('status', ['active', 'inactive', 'voided', 'expired'])->default('inactive');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }
}
