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
        Schema::create('submitlinks', function (Blueprint $table) {
            $table->id();
            $table->string('connection_type')->nullable();
            $table->string('chat_id')->nullable();
            $table->string('typeoflink')->nullable();
            $table->string('outlink_on')->nullable();
            $table->text('backlink_to')->nullable();
            $table->string('anchor_text')->nullable();
            $table->string('outlink_placed_on_your_website')->nullable();
            $table->string('acceptedby_to')->nullable();
            $table->string('acceptedby_from')->nullable();
            $table->string('chat_status')->nullable(); 
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
        Schema::dropIfExists('submitlinks');
    }
};
