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
        Schema::create('outlinks', function (Blueprint $table) {
            $table->id();
            $table->string('from_user_id');
            $table->string('to_user_id');
            $table->string('website_id');
            $table->string('website_url');
            $table->string('website_niche');
            $table->string('website_description');
            $table->string('status');
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
        Schema::dropIfExists('outlinks');
    }
};
