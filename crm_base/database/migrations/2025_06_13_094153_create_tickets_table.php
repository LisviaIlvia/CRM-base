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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->text('descrizione');
            $table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('stato', ['aperto', 'in_lavorazione', 'risolto', 'chiuso'])->default('aperto');
            $table->enum('priorita', ['bassa', 'media', 'alta', 'urgente'])->default('media');
            $table->enum('categoria', ['tecnico', 'amministrativo', 'commerciale', 'altro'])->default('tecnico');
            $table->timestamp('data_risoluzione')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
