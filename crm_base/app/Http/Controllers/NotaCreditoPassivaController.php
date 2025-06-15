<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\NotaCreditoPassivaExport;

class NotaCreditoPassivaController extends AbstractDocumentController
{
	protected bool $entrata = true;
	protected string $pattern = 'note-credito-passive';
	protected array $intestatari = ['fornitori'];
	protected array $tipi_intestatari = [
		['title' => 'Fornitori', 'value' => 'fornitori']
	];
	
	protected bool $export = true;
	
	protected array $indexSetup = [
		'plural' => 'Note di Credito Passive',
		'single' => 'Nota di Credito Passiva',
		'type' => 'f',
		'icon' => 'custom:note-credito-passive',
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
		'class' => NotaCreditoPassivaExport::class
	];
}