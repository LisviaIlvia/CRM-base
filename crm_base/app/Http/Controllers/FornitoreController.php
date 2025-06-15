<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractEntityController;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\FornitoreIndirizzoController;
use App\Http\Controllers\FornitoreReferenteController;

class FornitoreController extends AbstractEntityController
{
	protected string $prefix_code = 'FOR';
	protected array $profili = ['Azienda'];
	protected string $pattern = 'fornitori';
	protected string $controller_indirizzi = FornitoreIndirizzoController::class;
	protected string $controller_referenti = FornitoreReferenteController::class;
	
	protected array $indexSetup = [
		'plural' => 'Fornitori',
		'single' => 'Fornitore',
		'type' => 'm',
		'icon' => 'fa-solid fa-industry',
		'headers' => [
			['title' => 'Codice', 'key' => 'codice', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Indirizzo', 'key' => 'indirizzo', 'sortable' => true],
			['title' => 'Partita IVA', 'key' => 'partita_iva', 'sortable' => true],
			['title' => 'Codice Fiscale', 'key' => 'codice_fiscale', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	public function __construct(
		DdtUscitaController $ddtUscitaController, 
		DdtEntrataController $ddtEntrataController,
		OrdineAcquistoController $ordiniAcquistoController,
		FatturaAcquistoController $fattureAcquistoController,
		NotaCreditoPassivaController $noteCreditoPassivaController
	)
    {
        parent::__construct([
			$ddtUscitaController->getPattern() => $ddtUscitaController,
            $ddtEntrataController->getPattern() => $ddtEntrataController,
            $ordiniAcquistoController->getPattern() => $ordiniAcquistoController,
            $fattureAcquistoController->getPattern() => $fattureAcquistoController,
            $noteCreditoPassivaController->getPattern() => $noteCreditoPassivaController
        ]);
    }
}
