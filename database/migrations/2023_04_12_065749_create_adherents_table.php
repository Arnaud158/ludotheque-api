<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('valide')->default(true);
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('pseudo', 50);
            $table->string('avatar', 100)->default('avatars/user-default.svg');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adherents');
    }
};
