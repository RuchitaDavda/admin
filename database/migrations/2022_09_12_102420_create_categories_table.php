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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('parameter_types')->comment('1:Carpet Area 2:Built-Up Area 3:Plot Area 4:Hecta Area 5:Acre 6:House Type 7:Furnished 8:House No 9:Survey No 10:Plot No');
            $table->text('image');
            $table->tinyInteger('status')->comment('0:DeActive 1:Active')->default(0);
            $table->tinyInteger('sequence')->default(0);
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
        Schema::dropIfExists('categories');
    }
};
