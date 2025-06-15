<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

/**
 * TicketResponseController
 * 
 * Questo controller gestisce tutte le operazioni relative alle risposte ai ticket.
 * Estende AbstractCrudController che fornisce le funzionalità base CRUD.
 * Le risposte sono sempre associate a un ticket specifico.
 */
class TicketResponseController extends AbstractCrudController
{
    /**
     * @var string $pattern
     * Definisce il pattern delle rotte per questo controller.
     * Le risposte sono sempre associate a un ticket, quindi il pattern
     * sarà del tipo: /tickets/{ticket_id}/responses
     */
    protected string $pattern = 'ticket-responses';

    /**
     * @var string $permission
     * Definisce il permesso necessario per accedere a questo controller.
     * Utilizziamo lo stesso permesso dei ticket per semplicità.
     */
    protected string $permission = 'tickets';

    /**
     * @var string $model
     * Specifica il modello Eloquent associato a questo controller.
     * In questo caso, il modello TicketResponse.
     */
    protected string $model = TicketResponse::class;

    /**
     * @var array $indexSetup
     * Configurazione della vista lista delle risposte.
     * Definisce come apparirà la lista delle risposte a un ticket.
     */
    protected array $indexSetup = [
        'plural' => 'Risposte al Ticket',
        'single' => 'Risposta',
        'type' => 'ticket-responses',
        'icon' => 'chat',
        'headers' => [
            ['key' => 'user.name', 'label' => 'Autore'],        // Relazione con User
            ['key' => 'messaggio', 'label' => 'Messaggio'],
            ['key' => 'created_at', 'label' => 'Data risposta']
        ]
    ];

    /**
     * @var array $dialogSetup
     * Configurazione dei form e delle viste dettaglio.
     * Definisce come appariranno i form di creazione e visualizzazione.
     */
    protected array $dialogSetup = [
        'create' => [    // Form di creazione nuova risposta
            'title' => 'Nuova Risposta',
            'fields' => [
                ['key' => 'messaggio', 'label' => 'Messaggio', 'type' => 'textarea', 'required' => true]
            ]
        ],
        'show' => [      // Vista dettaglio risposta
            'title' => 'Dettaglio Risposta',
            'fields' => [
                ['key' => 'user.name', 'label' => 'Autore'],
                ['key' => 'messaggio', 'label' => 'Messaggio'],
                ['key' => 'created_at', 'label' => 'Data risposta']
            ]
        ]
    ];

    /**
     * setJsonData
     * 
     * Converte i dati della risposta in formato JSON per l'API.
     * Questo metodo viene chiamato automaticamente quando si restituiscono i dati.
     * 
     * @param string $type Il tipo di operazione (index, show, edit, etc.)
     * @param mixed $object L'oggetto TicketResponse da convertire
     * @return array I dati formattati
     */
    protected function setJsonData(string $type, $object)
    {
        $data = [
            'id' => $object->id,
            'ticket_id' => $object->ticket_id,
            'messaggio' => $object->messaggio,
            // Relazione con l'utente che ha scritto la risposta
            'user' => $object->user ? [
                'id' => $object->user->id,
                'name' => $object->user->name
            ] : null,
            'created_at' => $object->created_at,
            'updated_at' => $object->updated_at
        ];

        return $data;
    }

    /**
     * setOtherData
     * 
     * Fornisce dati aggiuntivi necessari per i form.
     * In questo caso, non abbiamo bisogno di dati aggiuntivi
     * poiché il form di risposta è molto semplice.
     * 
     * @param string $type Il tipo di operazione
     * @param mixed $object L'oggetto TicketResponse
     * @return array I dati aggiuntivi
     */
    protected function setOtherData(string $type, $object)
    {
        return [];
    }

    /**
     * beforeStore
     * 
     * Hook eseguito prima di salvare una nuova risposta.
     * Aggiunge automaticamente l'ID dell'utente corrente.
     * 
     * @param array &$validatedData I dati validati
     */
    protected function beforeStore(&$validatedData)
    {
        $validatedData['user_id'] = auth()->id();
    }
} 