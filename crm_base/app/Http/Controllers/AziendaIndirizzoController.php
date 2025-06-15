<?php

namespace App\Http\Controllers;

use App\Models\AziendaIndirizzo;
use App\Http\Controllers\Base\AbstractIndirizzoController;

class AziendaIndirizzoController extends AbstractIndirizzoController
{
	protected string $pattern = 'azienda-indirizzi';
	protected string $permission = 'azienda';
	protected string $table_id = 'azienda_id';
	protected string $model = AziendaIndirizzo::class;
	
	protected array $indexSetup = [
		'plural' => 'Indirizzi',
		'single' => 'Indirizzo Azienda',
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