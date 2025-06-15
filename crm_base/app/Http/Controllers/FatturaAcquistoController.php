<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\FatturaAcquistoExport;

class FatturaAcquistoController extends AbstractDocumentController
{
	protected bool $entrata = true;
	protected string $pattern = 'fatture-acquisto';
	protected array $intestatari = ['fornitori'];
	protected array $tipi_intestatari = [
		['title' => 'Fornitore', 'value' => 'fornitori']
	];
	
	protected bool $export = true;
	
	protected array $indexSetup = [
		'plural' => 'Fatture Acquisto',
		'single' => 'Fattura Acquisto',
		'type' => 'f',
		'icon' => 'custom:fatture-acquisto',
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
		'class' => FatturaAcquistoExport::class
	];
}