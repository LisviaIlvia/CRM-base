<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referente extends Model
{
    use SoftDeletes;
	
	protected $table = 'referenti';

    protected $fillable = [
        'nome',
        'cognome',
        'ruolo',
        'telefono',
        'email',
        'note',
		'entity_id'
    ];

	public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
