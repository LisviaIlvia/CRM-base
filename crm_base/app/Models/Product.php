<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
	
	protected $table = 'products';

    protected $fillable = [
		'type',
        'codice',
        'nome',
        'descrizione',
        'unita_misura',
        'prezzo',
        'aliquota_iva_id',
		'aliquota_iva_predefinita',
		'tax_in',
		'ricorrenza',
		'rinnovabile',
		'giacenza_iniziale'
    ];

	protected $casts = ['rinnovabile' => 'boolean', 'tax_in' => 'boolean', 'aliquota_iva_predefinita' => 'boolean'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_products');
    }
	
	public static function merci()
	{
		return self::where('type', 'merci');
	}
	
	public static function servizi()
	{
		return self::where('type', 'servizi');
	}
	
	public function aliquotaIva()
    {
        return $this->belongsTo(AliquotaIva::class, 'aliquota_iva_id');
    }
	
	public function documents()
	{
		return $this->hasManyThrough(
			Document::class,
			DocumentProduct::class,
			'product_id',
			'id',
			'id',
			'document_id'
		);
	}
	
	public function documentProduct()
	{
		return $this->hasMany(DocumentProduct::class, 'product_id');
	}
	
	public function documentProductFilter(string $type)
	{
		return $this->hasMany(DocumentProduct::class, 'product_id')
			->whereHas('document', function ($query) use ($type) {
				$query->where('type', $type);
			});
	}
	
	public function ddtEntrataProduct()
	{
		return $this->hasMany(DocumentProduct::class, 'product_id')
					->whereHas('document', function ($query) {
						$query->where('type', 'ddt-entrata');
					});
	}

    public function ddtUscitaProduct()
    {
		return $this->hasMany(DocumentProduct::class, 'product_id')
					->whereHas('document', function ($query) {
						$query->where('type', 'ddt-uscita');
					});
    }
	
	public function ordiniVenditaProduct()
	{
		return $this->hasMany(DocumentProduct::class, 'product_id')
					->whereHas('document', function ($query) {
						$query->where('type', 'ordini-vendita');
					});
	}
	
	public function fatturaVenditaProduct()
    {
		return $this->hasMany(DocumentProduct::class, 'product_id')
					->whereHas('document', function ($query) {
						$query->where('type', 'fatture-vendita');
					});
    }
}