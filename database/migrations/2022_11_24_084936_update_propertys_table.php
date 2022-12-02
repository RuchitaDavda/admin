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
        //
        Schema::table(
            'propertys',
            function (Blueprint $table) {
                // $table->dropColumn('carpet_area');
                // $table->dropColumn('built_up_area');
                // $table->dropColumn('plot_area');

                // $table->dropColumn('acre');
                // $table->dropColumn('carpet_area');


                // $table->boolean('parking')->comment('0=No,1=Yes');
                // $table->string('area_type');
                // $table->text('short_description');
                // $table->text('full_description');
                // $table->double('total_floor');
                // $table->string('video');
                // $table->string('full_imahe');
                // $table->string('interested_user');
                // $table->string('construction_status')->comment('1=under construction,2=complete construction');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
