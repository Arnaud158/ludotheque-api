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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->string('commentaire', 200);
            $table->dateTime('date_com')->default(now());
            $table->integer('note');
            $table->foreignIdFor(Adherent::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignIdFor(Jeu::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('commentaires');
    }
};
