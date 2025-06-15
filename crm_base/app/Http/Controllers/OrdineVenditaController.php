<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\OrdineVenditaExport;

class OrdineVenditaController extends AbstractDocumentController
{
	protected string $prefix_code = 'ODV';
	protected string $pattern = 'ordini-vendita';
	protected array $intestatari = ['clienti'];
	protected array $tipi_intestatari = [
		['title' => 'Cliente', 'value' => 'clienti']
	];
	protected bool $spedizione_active = true;
	protected bool $metodo_pagamento_active = true;
	protected bool $rate_active = true;
	protected bool $export = true;
	protected bool $pdf = true;
	protected bool $clone = true;
	protected bool $magic = true;
	
	protected array $indexSetup = [
		'plural' => 'Ordini Vendita',
		'single' => 'Ordine Vendita',
		'type' => 'm',
		'icon' => 'custom:ordini-vendita',
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
		['title' => 'DDT Uscita', 'value' => 'DdtUscitaController', 'type' => 'ddt-uscita'],
		['title' => 'Fattura Proforma', 'value' => 'FatturaProformaController', 'type' => 'fatture-proforma'],
		['title' => 'Fattura Vendita', 'value' => 'FatturaVenditaController', 'type' => 'fatture-vendita']
	];
	
	protected array $exportSetup = [
		'class' => OrdineVenditaExport::class
	];
}