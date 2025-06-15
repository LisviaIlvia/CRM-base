<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use SoftDeletes;
	
	protected $table = 'entities';

    protected $fillable = [
        'codice',
        'profilo',
        'nome',
        'type',
        'partita_iva',
        'codice_fiscale',
        'email',
        'telefono',
        'pec',
        'cuu',
        'note'
    ];
	
	public static function clienti()
	{
		return self::where('type', 'clienti');
	}
	
	public static function fornitori()
	{
		return self::where('type', 'fornitori');
	}
	
	public function indirizzi()
    {
        return $this->hasMany(Indirizzo::class, 'entity_id');
    }
	
	public function referenti()
    {
        return $this->hasMany(Referente::class, 'entity_id');
    }
	
	public function documents()
	{
		return $this->hasMany(Document::class, 'entity_id');
	}
	
	public function activities()
    {
        return $this->hasMany(Activity::class, 'entity_id');
    }
}
