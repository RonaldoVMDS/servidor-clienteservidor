<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRegisteredAtInOccurrences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->string('registered_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->timestamp('registered_at')->change();
        });
    }
}
