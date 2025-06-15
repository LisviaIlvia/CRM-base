<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('codice');
            $table->string('profilo');
            $table->string('nome');
            $table->string('partita_iva', 20)->nullable();
            $table->string('codice_fiscale')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('pec')->nullable();
            $table->string('cuu')->nullable();
            $table->text('note')->nullable();
			$table->string('type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('indirizzi', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nazione')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('codice_comune', 6)->nullable();
            $table->string('comune')->nullable();
            $table->string('provincia', 2)->nullable();
            $table->string('cap', 5)->nullable();
            $table->string('codice_regione', 2)->nullable();
            $table->string('regione')->nullable();
            $table->string('telefono')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('referenti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('ruolo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
		
		Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type');
			$table->date('data');
			$table->text('descrizione')->nullable();
			$table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
        Schema::dropIfExists('indirizzi');
        Schema::dropIfExists('referenti');
		Schema::dropIfExists('activities');
    }
};
