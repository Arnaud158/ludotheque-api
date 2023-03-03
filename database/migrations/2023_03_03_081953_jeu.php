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
    public function up() {
        Schema::create('jeus', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('description', 200);
            $table->string('regles', 200);
            $table->string('langue',50);
            $table->string('url_media', 100);
            $table->integer('age_min');
            $table->integer('nombre_joueurs_min');
            $table->integer('nombre_joueurs_max');
            $table->string('duree_partie',50);
            $table->boolean('valide')->default(true);
            $table->foreign('categorie_id')->references('id')->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('themes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('editeur_id')->references('id')->on('editeurs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('jeus');
    }
};
