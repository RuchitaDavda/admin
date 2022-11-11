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
        Schema::create('propertys', function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
        
            $table->string('title');
            $table->longText('description');
            $table->string('address');
            $table->string('client_address');
            $table->tinyInteger('propery_type')->comment('0:Sell 1:Rent');
            $table->string('price');
            $table->bigInteger('unit_type');
            $table->string('carpet_area')->nullable();
            $table->string('built_up_area')->nullable();
            $table->string('plot_area')->nullable();
            $table->string('hecta_area')->nullable();
            $table->string('acre')->nullable();
            $table->string('house_type')->nullable();
            $table->string('furnished')->nullable()->comment('0 : Furnished 1: Semi-Furnished 2: Not-Furnished');
            $table->string('state')->nullable();
            $table->string('district')->default('Kutch')->nullable();
            $table->string('taluka')->nullable();
            $table->string('village')->nullable();
            $table->text('title_image');
            $table->tinyInteger('added_by')->default(0);
            $table->tinyInteger('status')->default(0)->comment(' 0: Deactive 1: Active');
            $table->bigInteger('total_click')->default(0);
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
        Schema::dropIfExists('propertys');
    }
};
