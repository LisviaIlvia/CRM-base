<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Azienda extends Model
{
    use SoftDeletes;

    protected $table = 'azienda';
    protected $fillable = ['ragione_sociale', 'partita_iva', 'codice_fiscale', 'pec', 'logo'];

    public function indirizzi()
    {
        return $this->hasMany(AziendaIndirizzo::class, 'azienda_id');
    }
}