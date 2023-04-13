<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('editeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('editeurs');
    }
};
