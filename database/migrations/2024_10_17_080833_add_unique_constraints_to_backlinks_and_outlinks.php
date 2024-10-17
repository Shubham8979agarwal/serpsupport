<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintsToBacklinksAndOutlinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding unique constraints to the backlinks table
        Schema::table('backlinks', function (Blueprint $table) {
            $table->unique(['from_user_id', 'to_user_id'], 'unique_backlink_pair');
            $table->unique(['to_user_id', 'from_user_id'], 'unique_backlink_pair_reverse'); // For reverse pairs
        });

        // Adding unique constraints to the outlinks table
        Schema::table('outlinks', function (Blueprint $table) {
            $table->unique(['from_user_id', 'to_user_id'], 'unique_outlink_pair');
            $table->unique(['to_user_id', 'from_user_id'], 'unique_outlink_pair_reverse'); // For reverse pairs
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop unique constraints if rolling back
        Schema::table('backlinks', function (Blueprint $table) {
            $table->dropUnique('unique_backlink_pair');
            $table->dropUnique('unique_backlink_pair_reverse');
        });

        Schema::table('outlinks', function (Blueprint $table) {
            $table->dropUnique('unique_outlink_pair');
            $table->dropUnique('unique_outlink_pair_reverse');
        });
    }
}
