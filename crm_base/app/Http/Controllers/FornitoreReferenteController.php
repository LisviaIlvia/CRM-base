<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractReferenteController;

class FornitoreReferenteController extends AbstractReferenteController
{
	protected string $pattern = 'fornitori-referenti';
	protected string $permission = 'fornitori';
	
	protected array $indexSetup = [
		'plural' => 'Referenti',
		'single' => 'Referente Fornitore',
		'type' => 'm',
		'icon' => 'fa-solid fa-user-tie',
		'order' => [ 'key' => 'cognome', 'order' => 'asc' ],
		'headers' => [
			['title' => 'Cognome', 'key' => 'cognome', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Ruolo', 'key' => 'ruolo', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
}