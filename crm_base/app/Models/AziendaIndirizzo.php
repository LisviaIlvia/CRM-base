<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AziendaIndirizzo extends Model
{
    use SoftDeletes;

    protected $table = 'azienda_indirizzi';
    protected $fillable = ['nome', 'nazione', 'indirizzo', 'codice_comune', 'comune', 'provincia', 'cap', 'codice_regione', 'regione', 'telefono', 'note', 'azienda_id'];

    public function azienda()
    {
        return $this->belongsTo(Azienda::class, 'azienda_id');
    }
}