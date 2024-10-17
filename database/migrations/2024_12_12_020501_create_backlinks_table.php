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
        Schema::create('backlinks', function (Blueprint $table) {
            $table->id();
            $table->string('from_user_id')->nullable();
            $table->string('to_user_id')->nullable();
            $table->text('forwhich_user_url')->nullable();
            $table->string('website_id')->nullable();
            $table->string('website_url')->nullable();
            $table->string('website_niche')->nullable();
            $table->string('chat_id')->nullable();
            $table->string('chat_status')->nullable();
            $table->string('website_description')->nullable();
            $table->string('acceptedby_from')->nullable();
            $table->string('acceptedby_to')->nullable();
            $table->boolean('seen')->default(false);
            $table->string('status')->nullable(); 
            $table->timestamps();

            // Adding unique constraints directly here
            $table->unique(['from_user_id', 'to_user_id'], 'unique_backlink_pair');
            $table->unique(['to_user_id', 'from_user_id'], 'unique_backlink_pair_reverse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backlinks');
    }
};
