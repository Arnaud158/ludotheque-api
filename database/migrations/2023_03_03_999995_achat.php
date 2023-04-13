<?php

use App\Models\Adherent;
use App\Models\Jeu;
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
    public function up() {
        Schema::create('achats', function (Blueprint $table) {
            $table->foreignIdFor(Adherent::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignIdFor(Jeu::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('date_achat')->default(now());
            $table->string('lieu_achat', 50);
            $table->integer('prix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('achats');
    }
};
