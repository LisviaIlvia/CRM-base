<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * TicketController
 * 
 * Questo controller gestisce tutte le operazioni relative ai ticket di supporto.
 * Estende AbstractCrudController che fornisce le funzionalità base CRUD.
 */
class TicketController extends AbstractCrudController
{
    /**
     * @var string $pattern
     * Definisce il pattern delle rotte per questo controller.
     * Es: /tickets, /tickets/create, /tickets/{id}, ecc.
     */
    protected string $pattern = 'tickets';

    /**
     * @var string $permission
     * Definisce il permesso necessario per accedere a questo controller.
     * Verrà usato per il controllo degli accessi.
     */
    protected string $permission = 'tickets';

    /**
     * @var string $model
     * Specifica il modello Eloquent associato a questo controller.
     * In questo caso, il modello Ticket.
     */
    protected string $model = Ticket::class;

    /**
     * @var array $indexSetup
     * Configurazione della vista lista dei ticket.
     * Definisce come apparirà la tabella dei ticket.
     */
    protected array $indexSetup = [
        'plural' => 'Ticket di Supporto',    // Titolo della pagina
        'single' => 'Ticket',                // Nome singolare per i messaggi
        'type' => 'tickets',                 // Tipo per le rotte
        'icon' => 'ticket',                  // Icona da mostrare
        'headers' => [                       // Colonne della tabella
            ['key' => 'titolo', 'label' => 'Titolo'],
            ['key' => 'stato', 'label' => 'Stato'],
            ['key' => 'priorita', 'label' => 'Priorità'],
            ['key' => 'categoria', 'label' => 'Categoria'],
            ['key' => 'entity.nome', 'label' => 'Cliente'],      // Relazione con Entity
            ['key' => 'assignedUser.name', 'label' => 'Assegnato a'],  // Relazione con User
            ['key' => 'created_at', 'label' => 'Data creazione']
        ]
    ];

    /**
     * @var array $dialogSetup
     * Configurazione dei form e delle viste dettaglio.
     * Definisce come appariranno i form di creazione, modifica e visualizzazione.
     */
    protected array $dialogSetup = [
        'create' => [    // Form di creazione nuovo ticket
            'title' => 'Nuovo Ticket',
            'fields' => [
                ['key' => 'titolo', 'label' => 'Titolo', 'type' => 'text', 'required' => true],
                ['key' => 'descrizione', 'label' => 'Descrizione', 'type' => 'textarea', 'required' => true],
                ['key' => 'entity_id', 'label' => 'Cliente', 'type' => 'select', 'required' => true],
                ['key' => 'stato', 'label' => 'Stato', 'type' => 'select', 'required' => true],
                ['key' => 'priorita', 'label' => 'Priorità', 'type' => 'select', 'required' => true],
                ['key' => 'categoria', 'label' => 'Categoria', 'type' => 'select', 'required' => true],
                ['key' => 'assigned_to', 'label' => 'Assegnato a', 'type' => 'select']
            ]
        ],
        'edit' => [      // Form di modifica ticket esistente
            'title' => 'Modifica Ticket',
            'fields' => [
                // Campi simili a create, ma senza entity_id (non si può cambiare il cliente)
                ['key' => 'titolo', 'label' => 'Titolo', 'type' => 'text', 'required' => true],
                ['key' => 'descrizione', 'label' => 'Descrizione', 'type' => 'textarea', 'required' => true],
                ['key' => 'stato', 'label' => 'Stato', 'type' => 'select', 'required' => true],
                ['key' => 'priorita', 'label' => 'Priorità', 'type' => 'select', 'required' => true],
                ['key' => 'categoria', 'label' => 'Categoria', 'type' => 'select', 'required' => true],
                ['key' => 'assigned_to', 'label' => 'Assegnato a', 'type' => 'select']
            ]
        ],
        'show' => [      // Vista dettaglio ticket
            'title' => 'Dettaglio Ticket',
            'fields' => [
                // Campi di sola lettura per visualizzare i dettagli
                ['key' => 'titolo', 'label' => 'Titolo'],
                ['key' => 'descrizione', 'label' => 'Descrizione'],
                ['key' => 'stato', 'label' => 'Stato'],
                ['key' => 'priorita', 'label' => 'Priorità'],
                ['key' => 'categoria', 'label' => 'Categoria'],
                ['key' => 'entity.nome', 'label' => 'Cliente'],
                ['key' => 'assignedUser.name', 'label' => 'Assegnato a'],
                ['key' => 'created_at', 'label' => 'Data creazione'],
                ['key' => 'updated_at', 'label' => 'Ultima modifica']
            ]
        ]
    ];

    /**
     * setJsonData
     * 
     * Converte i dati del ticket in formato JSON per l'API.
     * Questo metodo viene chiamato automaticamente quando si restituiscono i dati.
     * 
     * @param string $type Il tipo di operazione (index, show, edit, etc.)
     * @param mixed $object L'oggetto Ticket da convertire
     * @return array I dati formattati
     */
    protected function setJsonData(string $type, $object)
    {
        $data = [
            'id' => $object->id,
            'titolo' => $object->titolo,
            'descrizione' => $object->descrizione,
            'stato' => $object->stato,
            'priorita' => $object->priorita,
            'categoria' => $object->categoria,
            // Relazione con il cliente (Entity)
            'entity' => $object->entity ? [
                'id' => $object->entity->id,
                'nome' => $object->entity->nome
            ] : null,
            // Relazione con l'utente assegnato
            'assignedUser' => $object->assignedUser ? [
                'id' => $object->assignedUser->id,
                'name' => $object->assignedUser->name
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
     * Questi dati vengono usati per popolare le liste di selezione.
     * 
     * @param string $type Il tipo di operazione
     * @param mixed $object L'oggetto Ticket
     * @return array I dati aggiuntivi
     */
    protected function setOtherData(string $type, $object)
    {
        return [
            // Lista dei clienti per il select
            'entities' => \App\Models\Entity::clienti()->get(['id', 'nome']),
            // Lista degli utenti per il select
            'users' => \App\Models\User::all(['id', 'name']),
            // Stati possibili per il ticket
            'stati' => ['aperto', 'in_lavorazione', 'risolto', 'chiuso'],
            // Priorità possibili
            'priorita' => ['bassa', 'media', 'alta', 'urgente'],
            // Categorie possibili
            'categorie' => ['tecnico', 'amministrativo', 'commerciale', 'altro']
        ];
    }

    /**
     * setValidation
     * 
     * Definisce le regole di validazione per i dati in ingresso.
     * Questo metodo viene chiamato automaticamente dal controller base.
     * 
     * @param mixed $object L'oggetto Ticket (se in fase di aggiornamento)
     * @return array Le regole di validazione
     */
    protected function setValidation($object = null)
    {
        $rules = [
            'titolo' => 'required|string|max:255',
            'descrizione' => 'required|string',
            'stato' => ['required', 'in:aperto,in_lavorazione,risolto,chiuso'],
            'priorita' => ['required', 'in:bassa,media,alta,urgente'],
            'categoria' => ['required', 'in:tecnico,amministrativo,commerciale,altro'],
            'assigned_to' => 'nullable|exists:users,id',
        ];

        // entity_id è richiesto solo in fase di creazione
        if (!$object) {
            $rules['entity_id'] = 'required|exists:entities,id';
        }

        return $rules;
    }
}