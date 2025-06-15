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
		Schema::create('azienda', function (Blueprint $table) {
			$table->id();
			$table->string('ragione_sociale');
			$table->string('partita_iva', 20);
			$table->string('codice_fiscale', 16);
			$table->string('pec');
			$table->string('logo')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('azienda');
    }
};
