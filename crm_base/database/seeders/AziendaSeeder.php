<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Azienda;
use App\Models\AziendaIndirizzo;
use Carbon\Carbon;

class AziendaSeeder extends Seeder
{
    /* php artisan db:seed --class=AziendaSeeder
       php artisan cache:clear
       php artisan optimize
       php artisan clear-compiled
    */

    public function run()
    {
        $azienda = Azienda::create([
            'ragione_sociale' => 'Dunp',
            'partita_iva' => '17361281003',
            'codice_fiscale' => '17361281003',
            'pec' => 'dunp@pec.it',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

		AziendaIndirizzo::create([
            'nome' => 'PRINCIPALE',
            'nazione' => 'ITALIA',
            'indirizzo' => 'Via nome indirizzo',
            'codice_comune' => '058091',
            'comune' => 'Roma',
            'provincia' => 'RM',
            'cap' => '00153',
            'codice_regione' => '12',
            'regione' => 'Lazio',
            'telefono' => '3758629795',
            'note' => '',
            'azienda_id' => $azienda->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
