<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Indirizzo;
use App\Models\Entity;

abstract class AbstractIndirizzoController extends AbstractCrudController 
{
	protected string $model = Indirizzo::class;
	
	protected array $dialogSetup = [
		'create' => [
			'width' => '60%'
		],
		'show' => [
			'width' => '60%'
		],
		'edit' => [
			'width' => '60%'
		]
	];
	
	protected function setComponents()
	{
		return [
			'index' => 'indirizzi/IndirizziIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'indirizzi/IndirizziContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['entity_id'] = $validatedData['element_id'];
		$validatedData['codice_regione'] = $validatedData['regione'];
		$validatedData['regione'] = $validatedData['nome_regione'];
		$validatedData['codice_comune'] = $validatedData['comune'];
		$validatedData['comune'] = $validatedData['nome_comune'];	
		unset($validatedData['element_id']);
		unset($validatedData['nome_regione']);
		unset($validatedData['nome_comune']);
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		$validatedData['codice_regione'] = $validatedData['regione'];
		$validatedData['regione'] = $validatedData['nome_regione'];
		$validatedData['codice_comune'] = $validatedData['comune'];
		$validatedData['comune'] = $validatedData['nome_comune'];	
		unset($validatedData['nome_regione']);
		unset($validatedData['nome_comune']);
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$element_id = request()->input('element_id');
			
		$main = [
			'id' => $object->id,
			'nome' => $object->nome,
			'comune' => $object->comune,
			'indirizzo' => $object->indirizzo
		];

		$data = match($type) {
			'create' => [
				'nome' => $this->getNameCreate($element_id), 
				'element_id' => $element_id
			],
			'show', 'edit' => [
				'nazione' => $object->nazione,
				'codice_regione' => $object->codice_regione,
				'regione' => $object->regione,
				'provincia' => $object->provincia,
				'codice_comune' => $object->codice_comune,
				'nome_comune' => $object->comune,
				'cap' => $object->cap,
				'note' => $object->note
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}

	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'nome' => 'required',
				'nazione' => 'nullable',
				'regione' => 'required_if:nazione,ITALIA',
				'nome_regione' => 'nullable',
				'comune' =>  'required_if:nazione,ITALIA',
				'nome_comune' => 'nullable',
				'provincia' => 'required_if:nazione,ITALIA',
				'cap' => 'nullable|numeric|required_if:nazione,ITALIA',
				'indirizzo' => 'required',
				'note' => 'nullable'
			],
			'store' => [
				'element_id' => 'required'
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'regione.required' => 'Il campo regione è obbligatorio.',
				'provincia.required' => 'Il campo provincia è obbligatorio.',
				'comune.required' => 'Il campo comune è obbligatorio.',
				'cap.required' => 'Il campo CAP è obbligatorio.',
				'cap.numeric' => 'Il campo CAP deve essere un numero.',
				'indirizzo.required' => 'Il campo indirizzo è obbligatorio.',
				'element_id.required' => 'Il campo element_id è obbligatorio.'
			]
		];
	}
	
	private function getNameCreate(int $element_id)
	{
		return $element_id 
			? (Entity::find($element_id)?->indirizzi->isEmpty() ? 'PRINCIPALE' : null) 
			: null;
	}
}