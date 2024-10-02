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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('from_user_id')->nullable();
            $table->string('to_user_id')->nullable();
            $table->text('forwhich_user_url')->nullable();
            $table->string('website_url')->nullable();
            //$table->string('acceptedby_from')->nullable();
            //$table->string('acceptedby_to')->nullable();
            $table->string('connnection_text')->nullable();
            $table->boolean('seen')->default(false);
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
        Schema::dropIfExists('notifications');
    }
};
