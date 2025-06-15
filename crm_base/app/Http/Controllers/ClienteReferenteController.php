<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractReferenteController;

class ClienteReferenteController extends AbstractReferenteController
{
	protected string $pattern = 'clienti-referenti';
	protected string $permission = 'clienti';
	
	protected array $indexSetup = [
		'plural' => 'Referenti',
		'single' => 'Referente Cliente',
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