<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountTypesToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('diskon_ps', 5, 2)->default(0)->after('diskon')->comment('Discount for PlayStation rental (percentage)');
            $table->decimal('diskon_fnb', 5, 2)->default(0)->after('diskon_ps')->comment('Discount for FnB (percentage)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['diskon_ps', 'diskon_fnb']);
        });
    }
}
