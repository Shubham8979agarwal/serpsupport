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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            //$table->string('username')->nullable();
            $table->string('email')->unique();
            //$table->string('image')->default('no-avatar.png');
            //$table->float('platformfee')->default(7.5);
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            //$table->rememberToken();
            $table->timestamps();
        });

        DB::table('admins')->insert(
        array(
            //'username' => 'admin',
            'email' => 'serpsupportadmin@gmail.com',
            'password' => Hash::make('serpsupportadmin@08'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
