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
		Schema::create('documents', function (Blueprint $table) {
			$table->id();
			$table->string('type');
			$table->string('stato')->nullable();
			$table->string('numero');
			$table->date('data');
			$table->text('note')->nullable();
			$table->foreignId('metodo_pagamento_id')->nullable()->constrained('metodi_pagamento');
			$table->foreignId('conto_bancario_id')->nullable()->constrained('conti_bancari');
			$table->foreignId('entity_id')->constrained('entities')->onDelete('cascade');
			$table->foreignId('parent_id')->nullable()->constrained('documents')->onDelete('cascade');
			$table->timestamps();
			$table->softDeletes();
		});
				
		Schema::create('documents_indirizzi', function (Blueprint $table) {
			$table->id();
			$table->string('nome')->nullable();
			$table->string('indirizzo')->nullable();
			$table->string('comune')->nullable();
			$table->string('provincia', 2)->nullable();
			$table->string('cap', 5)->nullable();
			$table->string('telefono')->nullable();
			$table->text('note')->nullable();
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_products', function (Blueprint $table) {
			$table->id();
			$table->integer('quantita');
			$table->decimal('prezzo', 8, 2);
			$table->string('tipo_sconto');
			$table->decimal('sconto', 8, 2)->default(0);
			$table->foreignId('aliquota_iva_id')->constrained('aliquote_iva');
			$table->string('ricorrenza');
			$table->integer('order');
			$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_altro', function (Blueprint $table) {
			$table->id();
			$table->text('nome');
			$table->integer('quantita');
			$table->string('unita_misura');
			$table->decimal('prezzo', 8, 2);
			$table->string('tipo_sconto');
			$table->decimal('sconto', 8, 2)->default(0);
			$table->foreignId('aliquota_iva_id')->constrained('aliquote_iva');
			$table->string('ricorrenza');
			$table->integer('order');
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_descrizioni', function (Blueprint $table) {
			$table->id();
			$table->text('descrizione');
			$table->integer('order');
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_spedizioni', function (Blueprint $table) {
			$table->id();
			$table->decimal('prezzo', 8, 2);
			$table->decimal('sconto', 8, 2);
			$table->foreignId('aliquota_iva_id')->constrained('aliquote_iva');
			$table->foreignId('spedizione_id')->constrained('spedizioni');
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_trasporto', function (Blueprint $table) {
			$table->id();
			$table->integer('colli')->nullable();
			$table->decimal('peso', 8, 2)->nullable();
			$table->string('causale')->nullable();
			$table->string('porto')->nullable();
			$table->string('a_cura')->nullable();
			$table->string('vettore')->nullable();
			$table->text('annotazioni')->nullable();
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
		
		Schema::create('documents_rate', function (Blueprint $table) {
			$table->id();
			$table->date('data')->nullable();
			$table->decimal('percentuale', 8, 2);
			$table->decimal('importo', 8, 2);
			$table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::dropIfExists('documents_rate');
		Schema::dropIfExists('documents_trasporto');
        Schema::dropIfExists('documents_spedizioni');
		Schema::dropIfExists('documents_descrizioni');
		Schema::dropIfExists('documents_altro');
		Schema::dropIfExists('documents_products');
		Schema::dropIfExists('documents_indirizzi');
		Schema::dropIfExists('documents');
    }
};
