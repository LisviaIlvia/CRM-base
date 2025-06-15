<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Referente;

class AbstractReferenteController extends AbstractCrudController 
{
	protected string $pattern = 'referenti';
	protected string $model = Referente::class;
	
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
			'index' => 'referenti/ReferentiIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'referenti/ReferentiContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['entity_id'] = $validatedData['element_id'];
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$main = [
			'id' => $object->id,
			'nome' => $object->nome,
			'cognome' => $object->cognome,
			'ruolo' => $object->ruolo
		];

		$data = match($type) {
			'show', 'edit' => [
				'telefono' => $object->telefono,
				'email' => $object->email,
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
				'cognome' => 'required',
				'ruolo' => 'nullable',
				'telefono' => 'nullable',
				'email' => 'nullable|email',
				'note' => 'nullable'
			],
			'store' => [
				'element_id' => 'required'
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'cognome.required' => 'Il campo cognome è obbligatorio.',
				'email.email' => 'Il formato dell\'email non è valido.',
				'element_id.required' => 'Il campo element_id è obbligatorio.'
			]
		];
	}
}