<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractActivityController;
use App\Models\Activity;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;

class ClienteActivityController extends AbstractActivityController 
{
	protected string $pattern = 'clienti-attivita';
	protected string $permission = 'attivita';
	
	protected array $indexSetup = [
		'plural' => 'Attività',
		'single' => 'Attività Cliente',
		'type' => 'f',
		'icon' => 'fa-solid fa-list-check',
		'order' => [ 'key' => 'data', 'order' => 'desc' ],
		'nameDialog' => 'type',
		'headers' => [
			['title' => 'Tipo', 'key' => 'type', 'sortable' => true],
			['title' => 'Data', 'key' => 'data', 'sortable' => true],
			['title' => 'descrizione', 'key' => 'descrizione', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];

}