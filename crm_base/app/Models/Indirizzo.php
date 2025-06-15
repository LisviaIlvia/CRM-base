<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indirizzo extends Model
{
    use SoftDeletes;
	
	protected $table = 'indirizzi';

    protected $fillable = [
        'nome',
        'nazione',
        'indirizzo',
        'codice_comune',
        'comune',
        'provincia',
        'cap',
        'codice_regione',
        'regione',
        'telefono',
        'note',
		'entity_id'
    ];
	
	public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
