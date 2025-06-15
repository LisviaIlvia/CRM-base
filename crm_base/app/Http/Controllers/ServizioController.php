<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractProductController;

class ServizioController extends AbstractProductController
{
	protected string $prefix_code = 'SER';
	protected string $pattern = 'servizi';
	protected bool $rinnovabile = true;
	protected array $ricorrenze = [
		[
			'value' => 'una_tantum',
			'title'=> 'Una tantum'
		],
		[
			'value' => 'mensile',
			'title'=> 'Mensile'
		],
		[
			'value' => 'trimestrale',
			'title'=> 'Trimestrale'
		],
		[
			'value' => 'semestrale',
			'title'=> 'Semestrale'
		],
		[
			'value' => 'annuale',
			'title'=> 'Annuale'
		]
	];
	
	protected array $indexSetup = [
		'plural' => 'Servizi',
		'single' => 'Servizio',
		'type' => 'm',
		'icon' => 'fa-solid fa-cogs',
		'headers' => [
			['title' => 'Codice', 'key' => 'codice', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	public function __construct(
		OrdineVenditaController $ordiniVenditaController,
		FatturaProformaController $fattureProformaController,
		FatturaVenditaController $fattureVenditaController,
		NotaCreditoAttivaController $noteCreditoAttivaController,
		OrdineAcquistoController $ordiniAcquistoController,
		FatturaAcquistoController $fattureAcquistoController,
		NotaCreditoPassivaController $noteCreditoPassivaController
	)
    {
        parent::__construct([
            $ordiniVenditaController->getPattern() => $ordiniVenditaController,
            $fattureProformaController->getPattern() => $fattureProformaController,
            $fattureVenditaController->getPattern() => $fattureVenditaController,
            $noteCreditoAttivaController->getPattern() => $noteCreditoAttivaController,
			$ordiniAcquistoController->getPattern() => $ordiniAcquistoController,
            $fattureAcquistoController->getPattern() => $fattureAcquistoController,
            $noteCreditoPassivaController->getPattern() => $noteCreditoPassivaController
        ]);
    }
}