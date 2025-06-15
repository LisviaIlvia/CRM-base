<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\FatturaVenditaExport;

class FatturaVenditaController extends AbstractDocumentController
{
	protected string $prefix_code = 'FTT';
	protected string $pattern = 'fatture-vendita';
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
		'plural' => 'Fatture Vendita',
		'single' => 'Fattura Vendita',
		'type' => 'f',
		'icon' => 'custom:fatture-vendita',
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
		['title' => 'Nota di Credito', 'value' => 'NotaCreditoAttivaController', 'type' => 'note-credito-attive']
	];
	
	protected array $exportSetup = [
		'class' => FatturaVenditaExport::class
	];
}