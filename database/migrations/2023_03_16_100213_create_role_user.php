<?php

use App\Models\Adherent;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adherent_role', function (Blueprint $table) {
            $table->primary(['adherent_id', 'role_id']);
            $table->foreignIdFor(Adherent::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignIdFor(Role::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adherent_role');
    }
};
