<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
	/* php artisan db:seed --class=PermissionSeeder
	   php artisan cache:clear
       php artisan optimize
       php artisan clear-compiled
	*/

     public function run()
    {
        $entities = [
		    'utenti',
			'ruoli',
			'azienda',
			'clienti',
			'fornitori',
			'aliquote-iva',
			'metodi-pagamento',
			'conti-bancari',
			'spedizioni',
			'merci',
			'servizi',
			'categorie',
			'ordini-vendita',
			'ddt-entrata',
			'ddt-uscita',
			'fatture-acquisto',
			'fatture-vendita',
			'fatture-proforma',
			'ordini-acquisto',
			'note-credito-attive',
			'note-credito-passive',
			'attivita',
            'tickets'
        ];

		$actions = ['show', 'create', 'edit', 'delete'];

		foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permissions[] = ['name' => "{$entity}.{$action}"];
            }
        }

        $permissions[] = ['name' => "ddt-uscita.clone"];
		$permissions[] = ['name' => "fatture-vendita.clone"];
		$permissions[] = ['name' => "ordini-vendita.clone"];
		$permissions[] = ['name' => "note-credito-attive.clone"];
		$permissions[] = ['name' => "fatture-proforma.clone"];
        $permissions[] = ['name' => "ddt-uscita.magic"];
		$permissions[] = ['name' => "fatture-vendita.magic"];
		$permissions[] = ['name' => "ordini-vendita.magic"];
		$permissions[] = ['name' => "note-credito-attive.magic"];
		$permissions[] = ['name' => "fatture-proforma.magic"];

        $permissions[] = ['name' => "ddt-uscita.export"];
		$permissions[] = ['name' => "fatture-vendita.export"];
		$permissions[] = ['name' => "ordini-vendita.export"];
		$permissions[] = ['name' => "note-credito-attive.export"];
		$permissions[] = ['name' => "fatture-proforma.export"];

        $permissions[] = ['name' => "ddt-entrata.export"];
		$permissions[] = ['name' => "fatture-acquisto.export"];
		$permissions[] = ['name' => "ordini-acquisto.export"];
		$permissions[] = ['name' => "note-credito-passive.export"];

		$permissions[] = ['name' => "magazzino.show"];
		$permissions[] = ['name' => "magazzino.export"];

        $permissions[] = ['name' => "ticket-responses.show"];
        $permissions[] = ['name' => "ticket-responses.create"];
        $permissions[] = ['name' => "ticket-responses.delete"];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}
