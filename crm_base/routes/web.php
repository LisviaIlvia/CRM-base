<?php

use App\Helpers\CrudRoutePermissionHelper as CrudRoutePermission;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
	Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
	
	CrudRoutePermission::resource('utenti', 'user', App\Http\Controllers\UserController::class);
	CrudRoutePermission::resource('ruoli', 'role', App\Http\Controllers\RoleController::class);
	
	CrudRoutePermission::resource('aliquote-iva', 'aliquota_iva', App\Http\Controllers\AliquotaIvaController::class);
	CrudRoutePermission::resource('metodi-pagamento', 'metodo_pagamento', App\Http\Controllers\MetodoPagamentoController::class);
	CrudRoutePermission::resource('conti-bancari', 'conto_bancario', App\Http\Controllers\ContoBancarioController::class);
	CrudRoutePermission::resource('spedizioni', 'spedizione', App\Http\Controllers\SpedizioneController::class);
	
	CrudRoutePermission::resource('categorie', 'category', App\Http\Controllers\CategoryController::class);
	CrudRoutePermission::resource('merci', 'merce', App\Http\Controllers\MerceController::class);
	CrudRoutePermission::resource('servizi', 'servizio', App\Http\Controllers\ServizioController::class);
		
	CrudRoutePermission::show('azienda', null, App\Http\Controllers\AziendaController::class, 'azienda', true);
	CrudRoutePermission::edit('azienda', 'azienda', App\Http\Controllers\AziendaController::class, 'azienda');
	
	CrudRoutePermission::resource('azienda-indirizzi', 'azienda_indirizzo', App\Http\Controllers\AziendaIndirizzoController::class, [
		'permission' => 'azienda'
	]);
	
	CrudRoutePermission::resource('clienti', 'cliente', App\Http\Controllers\ClienteController::class);
	CrudRoutePermission::resource('clienti-indirizzi', 'cliente_indirizzo', App\Http\Controllers\ClienteIndirizzoController::class, [
		'permission' => 'clienti'
	]);
	CrudRoutePermission::resource('clienti-referenti', 'cliente_referente', App\Http\Controllers\ClienteReferenteController::class, [
		'permission' => 'clienti'
	]);
	CrudRoutePermission::resource('clienti-attivita', 'cliente_activity', App\Http\Controllers\ClienteActivityController::class, [
		'permission' => 'attivita'
	]);
	
	CrudRoutePermission::resource('fornitori', 'fornitore', App\Http\Controllers\FornitoreController::class);
	CrudRoutePermission::resource('fornitori-indirizzi', 'fornitore_indirizzo', App\Http\Controllers\FornitoreIndirizzoController::class, [
		'permission' => 'fornitori'
	]);
	CrudRoutePermission::resource('fornitori-referenti', 'fornitore_referente', App\Http\Controllers\FornitoreReferenteController::class, [
		'permission' => 'fornitori'
	]);
	
	CrudRoutePermission::resource('ddt-uscita', 'ddt_uscita', App\Http\Controllers\DdtUscitaController::class,['export' => true, 'pdf' => true, 'magic' => true, 'clone' => true]);
	CrudRoutePermission::resource('ddt-entrata', 'ddt_entrata', App\Http\Controllers\DdtEntrataController::class,['export' => true]);
	
	CrudRoutePermission::resource('ordini-vendita', 'ordine_vendita', App\Http\Controllers\OrdineVenditaController::class,['export' => true, 'pdf' => true, 'magic' => true]);
	CrudRoutePermission::resource('ordini-acquisto', 'ordine_acquisto', App\Http\Controllers\OrdineAcquistoController::class,['export' => true]);
	
	CrudRoutePermission::resource('fatture-proforma', 'fattura_proforma', App\Http\Controllers\FatturaProformaController::class,['export' => true, 'pdf' => true, 'magic' => true]);
	CrudRoutePermission::resource('fatture-vendita', 'fattura_vendita', App\Http\Controllers\FatturaVenditaController::class,['export' => true, 'pdf' => true, 'magic' => true]);
	CrudRoutePermission::resource('fatture-acquisto', 'fattura_acquisto', App\Http\Controllers\FatturaAcquistoController::class,['export' => true]);
	
	CrudRoutePermission::resource('note-credito-attive', 'nota_credito_attiva', App\Http\Controllers\NotaCreditoAttivaController::class,['export' => true, 'pdf' => true]);
	CrudRoutePermission::resource('note-credito-passive', 'nota_credito_passiva', App\Http\Controllers\NotaCreditoPassivaController::class,['export' => true]);
	
	// Rotte per i ticket di supporto
	CrudRoutePermission::resource('tickets', 'ticket', App\Http\Controllers\TicketController::class);
	
	// Rotte per le risposte ai ticket
	CrudRoutePermission::resource('ticket-responses', 'ticket_response', App\Http\Controllers\TicketResponseController::class, [
		'permission' => 'tickets',
		'routes_excluded' => ['edit', 'update'] // Le risposte non possono essere modificate
	]);
	
	CrudRoutePermission::show('magazzino', null, App\Http\Controllers\MagazzinoController::class, 'magazzino', true);
	CrudRoutePermission::export('magazzino',  null, App\Http\Controllers\MagazzinoController::class, 'magazzino');
});

require __DIR__.'/auth.php';
require __DIR__.'/storage.php';