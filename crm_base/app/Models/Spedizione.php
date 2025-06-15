<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spedizione extends Model
{
	use SoftDeletes;

    protected $table = 'spedizioni';
    protected $fillable = ['nome', 'descrizione', 'prezzo', 'aliquota_iva_id', 'predefinita'];

    protected $casts = ['predefinita' => 'boolean'];

	public function aliquotaIva()
    {
        return $this->belongsTo(AliquotaIva::class, 'aliquota_iva_id');
    }

	public function documentSpedizione()
	{
		return $this->hasMany(DocumentSpedizione::class, 'spedizione_id');
	}
}
