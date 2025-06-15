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
		Schema::create('spedizioni', function (Blueprint $table) {
			$table->id();
			$table->string('nome');
			$table->text('descrizione')->nullable();
			$table->decimal('prezzo', 8, 2);
			$table->foreignId('aliquota_iva_id')->constrained('aliquote_iva');
			$table->boolean('predefinita');
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spedizioni');
    }
};
