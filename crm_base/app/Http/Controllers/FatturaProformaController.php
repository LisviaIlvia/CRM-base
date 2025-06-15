<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\FatturaProformaExport;

class FatturaProformaController extends AbstractDocumentController
{
	protected string $prefix_code = 'FTP';
	protected string $pattern = 'fatture-proforma';
	protected array $intestatari = ['clienti'];
	protected array $tipi_intestatari = [
		['title' => 'Cliente', 'value' => 'clienti']
	];
	protected bool $spedizione_active = true;
	protected bool $metodo_pagamento_active = true;
	protected bool $export = true;
	protected bool $pdf = true;
	protected bool $clone = true;
	protected bool $magic = true;
	
	protected array $indexSetup = [
		'plural' => 'Fatture Proforma',
		'single' => 'Fattura Proforma',
		'type' => 'f',
		'icon' => 'custom:fatture-proforma',
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
		['title' => 'Fattura Vendita', 'value' => 'FatturaVenditaController', 'type' => 'fatture-vendita']
	];
	
	protected array $exportSetup = [
		'class' => FatturaProformaExport::class
	];
}