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
		Schema::create('products', function (Blueprint $table) {
            $table->id();
			$table->string('type');
            $table->string('codice');
            $table->string('nome');
            $table->text('descrizione')->nullable();
            $table->string('unita_misura');
            $table->decimal('prezzo', 8, 2);
            $table->foreignId('aliquota_iva_id')->constrained('aliquote_iva');
            $table->boolean('aliquota_iva_predefinita')->default(1);
            $table->boolean('tax_in')->default(0);
			$table->string('ricorrenza');
			$table->boolean('rinnovabile')->default(0);
			$table->integer('giacenza_iniziale')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('categories_products', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->primary(['category_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
    }
};
