<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractIndirizzoController;

class ClienteIndirizzoController extends AbstractIndirizzoController
{
	protected string $pattern = 'clienti-indirizzi';
	protected string $permission = 'clienti';
	
	protected array $indexSetup = [
		'plural' => 'Indirizzi',
		'single' => 'Indirizzo Cliente',
		'type' => 'm',
		'icon' => 'fa-solid fa-map-location',
		'order' => [ 'key' => 'nome', 'order' => 'asc' ],
		'headers' => [
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Comune', 'key' => 'comune', 'sortable' => true],
			['title' => 'Indirizzo', 'key' => 'indirizzo', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
}