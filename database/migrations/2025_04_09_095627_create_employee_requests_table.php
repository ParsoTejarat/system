<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('title');
            $table->string('type');
            $table->string('amount')->nullable();
            $table->string('employee_attachment_path')->nullable();
            $table->string('approver_attachment_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'not_approved']);
            $table->longText('employee_description')->nullable();
            $table->longText('approver_description')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('employee_requests');
    }
}
