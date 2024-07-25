<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastOutlinksBacklinksColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_outlinks_created_at')->nullable();
            $table->timestamp('last_backlinks_created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_outlinks_created_at');
            $table->dropColumn('last_backlinks_created_at');
        });
    }
}

