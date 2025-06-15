<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractEntityController;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ClienteIndirizzoController;
use App\Http\Controllers\ClienteReferenteController;
use App\Http\Controllers\ClienteActivityController;

class ClienteController extends AbstractEntityController
{
	protected string $prefix_code = 'CLI';
	protected string $pattern = 'clienti';
	protected string $controller_indirizzi = ClienteIndirizzoController::class;
	protected string $controller_referenti = ClienteReferenteController::class;
	protected string $controller_activities = ClienteActivityController::class;
	protected bool $activity = true;
	
	protected array $indexSetup = [
		'plural' => 'Clienti',
		'single' => 'Cliente',
		'type' => 'm',
		'icon' => 'fa-solid fa-handshake',
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
		OrdineVenditaController $ordiniVenditaController,
		FatturaProformaController $fattureProformaController,
		FatturaVenditaController $fattureVenditaController,
		NotaCreditoAttivaController $noteCreditoAttivaController
	)
    {
        parent::__construct([
			$ddtUscitaController->getPattern() => $ddtUscitaController,
            $ddtEntrataController->getPattern() => $ddtEntrataController,
            $ordiniVenditaController->getPattern() => $ordiniVenditaController,
            $fattureProformaController->getPattern() => $fattureProformaController,
            $fattureVenditaController->getPattern() => $fattureVenditaController,
            $noteCreditoAttivaController->getPattern() => $noteCreditoAttivaController
        ]);
    }
}