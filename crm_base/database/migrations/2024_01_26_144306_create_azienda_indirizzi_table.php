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
		Schema::create('azienda_indirizzi', function (Blueprint $table) {
			$table->id();
			$table->string('nome');
			$table->string('nazione');
			$table->string('indirizzo');
			$table->string('codice_comune', 6);
			$table->string('comune');
			$table->string('provincia', 2);
			$table->string('cap', 5);
			$table->string('codice_regione', 2);
			$table->string('regione');
			$table->string('telefono')->nullable();
			$table->text('note')->nullable();
			$table->foreignId('azienda_id')->constrained('azienda')->onDelete('cascade');
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('azienda_indirizzi');
    }
};
