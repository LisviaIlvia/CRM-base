<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractIndirizzoController;

class FornitoreIndirizzoController extends AbstractIndirizzoController
{
	protected string $pattern = 'fornitori-indirizzi';
	protected string $permission = 'fornitori';
	
	protected array $indexSetup = [
		'plural' => 'Indirizzi',
		'single' => 'Indirizzo Fornitore',
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