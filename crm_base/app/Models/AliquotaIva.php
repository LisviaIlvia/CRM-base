<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AliquotaIva extends Model
{
	use SoftDeletes;

    protected $table = 'aliquote_iva';
    protected $fillable = ['aliquota', 'nome', 'descrizione', 'predefinita'];

    protected $casts = ['predefinita' => 'boolean'];

	public function productsPrice()
	{
		return $this->hasMany(ProductPrice::class, 'aliquota_iva_id');
	}

	/*public function documentProduct()
	{
		return $this->hasMany(DocumentProduct::class, 'aliquota_iva_id');
	}

	public function documentAltro()
	{
		return $this->hasMany(DocumentProduct::class, 'aliquota_iva_id');
	}*/

	public function spedizioni()
	{
		return $this->hasMany(Product::class, 'aliquota_iva_id');
	}
}
