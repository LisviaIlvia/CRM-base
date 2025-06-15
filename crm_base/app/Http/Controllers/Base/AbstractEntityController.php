<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Helpers\FunctionsHelper;
use App\Models\Entity;
use App\Models\Indirizzo;
use App\Models\Referente;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractEntityController extends AbstractCrudController 
{
	protected string $prefix_code = '';
	protected string $controller_indirizzi;
	protected string $controller_referenti;
	protected bool $createData = true;
	protected string $controller_activities = '';
	protected string $model = Entity::class;
	protected array $profili = ['Azienda', 'Privato'];
	protected bool $activity = false;
	protected array $verifyDestroy = ['documents'];
	
	protected array $documents = [];
	
	protected array $dialogSetup = [
		'create' => [
			'width' => '80%'
		],
		'show' => [
			'width' => '100%',
			'fullscreen' => true,
			'scrim' => false
		],
		'edit' => [
			'width' => '100%',
			'fullscreen' => true,
			'scrim' => false
		]
	];
	
	public function __construct(array $documents = []) {
        $this->documents = $documents;
    }
	
	protected function getCollectionIndex() 
	{
		return $this->model::{$this->pattern}()->get();
	}
	
	protected function setComponents()
	{
		return [
			'index' => 'Crud/CrudIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'EntitiesContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['type'] = $this->pattern;
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		$validatedData['type'] = $this->pattern;
	}

	protected function afterStore(&$object, $validatedData) 
	{
		if(array_key_exists('comune', $validatedData['indirizzo'])) {
			$indirizzo = $validatedData['indirizzo'];
			$indirizzo['codice_regione'] = $indirizzo['regione'];
			$indirizzo['regione'] = $indirizzo['nome_regione'];
			$indirizzo['codice_comune'] = $indirizzo['comune'];
			$indirizzo['comune'] = $indirizzo['nome_comune'];	
			unset($indirizzo['nome_regione']);
			unset($indirizzo['nome_comune']);

			$object->indirizzi()->create($indirizzo);
		}
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$documents = [];
		$all_documents = $object->documents;
		
		if(count($this->documents) > 0) {
			foreach($this->documents as $key => $document) {
				$filtered_documents = $all_documents->where('type', $key)->values();
				$documents[$key] = $document->indexDocFilter($filtered_documents);
			}
		}
		
		$main = [
			'id' => $object->id,
			'codice' => $object->codice,
			'nome' => $object->nome,
			'partita_iva' => $object->partita_iva,
			'codice_fiscale' => $object->codice_fiscale
		];

		$data = match($type) {
			'index', 'store', 'update' => [
				'indirizzo' => $object->indirizzi && $object->indirizzi->isNotEmpty()
					? $object->indirizzi[0]->indirizzo . ' - ' . $object->indirizzi[0]->comune 
					: null
			],
			'create' => [
				'codice' => FunctionsHelper::getLastCode($this->prefix_code, $this->model)
			],
			'show' => array_merge($this->getShowEditFields($object), [
				'indirizzi' => $this->getIndirizzi($object, ['show' => 'show'], false),
				'referenti' => $this->getReferenti($object, ['show' => 'show'], false),
				'attivita' => $this->controller_activities ? $this->getAttivita($object, ['show' => 'show'], false) : null,
				'activity' => $this->activity,
				'documents' => $documents
			]),
			'edit' => array_merge($this->getShowEditFields($object), [
				'indirizzi' => $this->getIndirizzi($object),
				'referenti' => $this->getReferenti($object),
				'attivita' => $this->controller_activities ? $this->getAttivita($object) : null,
				'activity' => $this->activity
			]),
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setOtherData(string $type, Model $object) 
	{	
		$data['profili'] = $this->profili;
		
		if($type == 'create') $data['indirizzo'] = ['resource' => ['nome' => 'PRINCIPALE']];

		return $data;
	}
	
	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'nome' => 'required',
				'profilo' => 'required',
				'email' => 'nullable|email',
				'telefono' => 'nullable',
				'note' => 'nullable'
			],
			'store' => [
				'codice' => [
					'required',
					Rule::unique('entities', 'codice')
					->whereNull('deleted_at')
				],
				'partita_iva' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'required',
					Rule::unique('entities', 'partita_iva')
					->whereNull('deleted_at')
				],
				'codice_fiscale' => [
					'required',
					Rule::unique('entities', 'codice_fiscale')
					->whereNull('deleted_at')
				],
				'pec' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'nullable',
					Rule::unique('entities', 'pec')
					->whereNull('deleted_at')
				],
				'cuu' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'nullable',
					Rule::unique('entities', 'cuu')
					->whereNull('deleted_at')
				],
				'indirizzo' => 'nullable'
			],
			'update' => [
				'codice' => [
					'required',
					Rule::unique('entities', 'codice')
					->ignore($object->id)
					->whereNull('deleted_at')
				],
				'partita_iva' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'required',
					Rule::unique('entities', 'partita_iva')
					->ignore($object->id)
					->whereNull('deleted_at')
				],
				'codice_fiscale' => [
					'required',
					Rule::unique('entities', 'codice_fiscale')
					->ignore($object->id)
					->whereNull('deleted_at')
				],
				'pec' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'nullable',
					Rule::unique('entities', 'pec')
					->ignore($object->id)
					->whereNull('deleted_at')
				],
				'cuu' => [
					Rule::excludeIf(request()->profilo === 'Privato'),
					'nullable',
					Rule::unique('entities', 'cuu')
					->ignore($object->id)
					->whereNull('deleted_at')
				]
			],
			'messages' => [
				'codice.required' => 'Il codice è obbligatorio.',
				'codice.unique' => 'Il codice inserito è già in uso.',
				'nome.required' => 'Il nome è obbligatorio.',
				'profilo.required' => 'Il profilo è obbligatorio.',
				'partita_iva.required' => 'La partita IVA è obbligatoria.',
				'partita_iva.unique' => 'La partita IVA inserita è già in uso.',
				'codice_fiscale.required' => 'Il codice fiscale è obbligatorio.',
				'codice_fiscale.unique' => 'Il codice fiscale inserito è già in uso.',
				'email.email' => 'Inserire un indirizzo email valido.',
				'pec.email' => 'Inserire un indirizzo PEC valido.',
				'pec.unique' => 'L\'indirizzo PEC inserito è già in uso.',
				'cuu.unique' => 'Il codice CUU inserito è già in uso.'
			]
		];
	}
	
	private function getShowEditFields(Model $object)
	{
		return [
			'type' => $object->type,
			'profilo' => $object->profilo,
			'email' => $object->email,
			'telefono' => $object->telefono,
			'pec' => $object->pec,
			'cuu' => $object->cuu,
			'note' => $object->note
		];
	}
	
	private function getElements(
		Model $object, 
		string $relation, 
		string $controllerProperty, 
		array $actionPermissions = [
			'show' => 'show',
			'edit' => 'edit',
			'update' => 'edit',
			'destroy' => 'delete'
		],
		bool $create = true
	) 
	{
		$defaultActions = ['show', 'edit', 'update', 'destroy'];
		foreach ($defaultActions as $action) {
			if (!array_key_exists($action, $actionPermissions)) {
				$actionPermissions[$action] = null;
			}
		}
		
		return (new $this->$controllerProperty())->getPropsIndex($object->$relation, $actionPermissions, $create);
	}

	private function getIndirizzi(Model $object, array $actionPermissions = ['show' => 'show', 'edit' => 'edit', 'update' => 'edit', 'destroy' => 'delete'], bool $create = true)
	{
		return $this->getElements($object, 'indirizzi', 'controller_indirizzi', $actionPermissions, $create);
	}

	private function getReferenti(Model $object, array $actionPermissions = ['show' => 'show', 'edit' => 'edit', 'update' => 'edit', 'destroy' => 'delete'], bool $create = true)
	{
		return $this->getElements($object, 'referenti', 'controller_referenti', $actionPermissions, $create);
	}
	
	private function getAttivita(Model $object, array $actionPermissions = ['show' => 'show', 'edit' => 'edit', 'update' => 'edit', 'destroy' => 'delete'], bool $create = true)
	{
		return $this->getElements($object, 'activities', 'controller_activities', $actionPermissions, $create);
	}

}