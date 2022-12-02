<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('user_contact');
            $table->string('user_email');
            $table->integer('user_id');
            $table->string('approved_by');
            $table->string('advertisment_type')->comment('1=slider,2=home,3=pagelisting');
            $table->string('amount_type')->comment('1=razorPay,2=payStack,3=flutterwave');
            $table->string('transaction_id');
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
        Schema::dropIfExists('advertisements');
    }
};
