<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractProductController;

class MerceController extends AbstractProductController
{
	protected string $prefix_code = 'MER';
	protected string $pattern = 'merci';
	protected bool $giacenza = true;
	protected array $ricorrenze = [
		[
			'value' => 'una_tantum',
			'title'=> 'Una tantum'
		]
	];
	
	protected array $indexSetup = [
		'plural' => 'Merci',
		'single' => 'Merce',
		'type' => 'f',
		'icon' => 'fa-solid fa-dolly',
		'headers' => [
			['title' => 'Codice', 'key' => 'codice', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	public function __construct(
		DdtUscitaController $ddtUscitaController, 
		DdtEntrataController $ddtEntrataController,
		OrdineVenditaController $ordiniVenditaController,
		FatturaProformaController $fattureProformaController,
		FatturaVenditaController $fattureVenditaController,
		NotaCreditoAttivaController $noteCreditoAttivaController,
		OrdineAcquistoController $ordiniAcquistoController,
		FatturaAcquistoController $fattureAcquistoController,
		NotaCreditoPassivaController $noteCreditoPassivaController,
	)
    {
        parent::__construct([
			$ddtUscitaController->getPattern() => $ddtUscitaController,
            $ddtEntrataController->getPattern() => $ddtEntrataController,
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