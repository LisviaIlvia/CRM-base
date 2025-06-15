<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPagamento extends Model
{
	use SoftDeletes;
	
    protected $table = 'metodi_pagamento';
    protected $fillable = ['nome', 'giorni', 'predefinito'];
	
	public function documents()
	{
		return $this->hasMany(Document::class, 'metodo_pagamento_id');
	}
}