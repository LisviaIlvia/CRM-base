<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractDocumentController;
use App\Exports\NotaCreditoAttivaExport;

class NotaCreditoAttivaController extends AbstractDocumentController
{
	protected string $prefix_code = 'NDC';
	protected string $pattern = 'note-credito-attive';
	protected array $intestatari = ['clienti'];
	protected array $tipi_intestatari = [
		['title' => 'Cliente', 'value' => 'clienti']
	];
	protected bool $export = true;
	protected bool $pdf = true;
	protected bool $clone = true;
	protected bool $metodo_pagamento_active = true;
	
	protected array $indexSetup = [
		'plural' => 'Note di Credito Attive',
		'single' => 'Nota di Credito Attiva',
		'type' => 'f',
		'icon' => 'custom:note-credito-attive',
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
	
	protected array $exportSetup = [
		'class' => NotaCreditoAttivaExport::class
	];
}