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
        Schema::table('propertys', function (Blueprint $table) {

            $table->string('house_no')->nullable()->after('furnished');
            $table->string('survey_no')->nullable()->after('house_no');
            $table->string('plot_no')->nullable()->after('survey_no');
            $table->string('latitude')->nullable()->after('total_click');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propertys', function (Blueprint $table) {
            //
        });
    }
};
