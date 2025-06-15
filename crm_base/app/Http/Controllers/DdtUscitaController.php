<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\DdtUscitaExport;

class DdtUscitaController extends AbstractDocumentController
{
	protected string $prefix_code = 'DDT';
	protected string $pattern = 'ddt-uscita';
	protected array $intestatari = ['clienti', 'fornitori'];
	protected array $tipi_intestatari = [
		['title' => 'Cliente', 'value' => 'clienti'], 
		['title' => 'Fornitore', 'value' => 'fornitori']
	];
	protected bool $spedizione_active = true;
	protected bool $trasporto_active = true;
	protected bool $export = true;
	protected bool $pdf = true;
	protected bool $clone = true;
	protected bool $magic = true;
	
	protected array $indexSetup = [
		'plural' => 'DDT Uscita',
		'single' => 'DDT Uscita',
		'type' => 'm',
		'icon' => 'custom:ddt-uscita',
		'order' => [ 'key' => 'data', 'order' => 'asc' ],
		'nameDialog' => 'numero',
		'headers' => [
			['title' => 'Numero', 'key' => 'numero', 'sortable' => true],
			['title' => 'Data', 'key' => 'data', 'sortable' => true],
			['title' => 'Destinatario', 'key' => 'destinatario', 'sortable' => true],
			['title' => 'Imponibile', 'key' => 'imponibile', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected array $types_relation = [
		['title' => 'Fattura Proforma', 'value' => 'FatturaProformaController', 'type' => 'fatture-proforma'],
		['title' => 'Fattura Vendita', 'value' => 'FatturaVenditaController', 'type' => 'fatture-vendita']
	];
	
	protected array $exportSetup = [
		'class' => DdtUscitaExport::class
	];
}