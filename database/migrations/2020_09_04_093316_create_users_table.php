<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // These are the columns of my user model, this is jsut a basic info of the user.
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('address');
            $table->string('contact');
            $table->string('bday');
            $table->string('gender');
            $table->string('bio')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('account_status')->default(true);
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
        Schema::dropIfExists('users');
    }
}
