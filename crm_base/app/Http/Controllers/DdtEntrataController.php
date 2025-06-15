<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\DdtEntrataExport;

class DdtEntrataController extends AbstractDocumentController
{
	protected bool $entrata =  true;
	protected bool $trasporto_active = true;
	
	protected string $pattern = 'ddt-entrata';
	protected array $intestatari = ['clienti', 'fornitori'];
	protected array $tipi_intestatari = [
		['title' => 'Cliente', 'value' => 'clienti'], 
		['title' => 'Fornitore', 'value' => 'fornitori']
	];
	
	protected bool $export = true;
	
	protected array $indexSetup = [
		'plural' => 'DDT Entrata',
		'single' => 'DDT Entrata',
		'type' => 'm',
		'icon' => 'custom:ddt-entrata',
		'order' => [ 'key' => 'data', 'order' => 'asc' ],
		'nameDialog' => 'numero',
		'headers' => [
			['title' => 'Numero', 'key' => 'numero', 'sortable' => true],
			['title' => 'Data', 'key' => 'data', 'sortable' => true],
			['title' => 'Mittente', 'key' => 'mittente', 'sortable' => true],
			['title' => 'Imponibile', 'key' => 'imponibile', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected array $exportSetup = [
		'class' => DdtEntrataExport::class
	];
}