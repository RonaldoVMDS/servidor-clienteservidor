<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropForeign('occurrences_user_id_foreign');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
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
            $table->dropForeign('occurrences_user_id_foreign');
            $table->foreign('user_id')
                  ->references('id')->on('users');
        });
    }
}
