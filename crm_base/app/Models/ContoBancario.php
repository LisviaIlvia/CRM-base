<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContoBancario extends Model
{
	use SoftDeletes;

    protected $table = 'conti_bancari';
    protected $fillable = ['nome', 'iban', 'predefinito'];

    protected $casts = ['predefinito' => 'boolean'];

	public function documents()
	{
		return $this->hasMany(Document::class, 'conto_bancario_id');
	}
}
