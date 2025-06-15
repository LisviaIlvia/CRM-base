<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\MetodoPagamento;
use Illuminate\Validation\Rule;

class MetodoPagamentoController extends AbstractCrudController 
{
	protected string $pattern = 'metodi-pagamento';
	protected string $model = MetodoPagamento::class;
	protected array $verifyDestroy = ['documents'];
	
	protected array $indexSetup = [
		'plural' => 'Metodi di pagamento',
		'single' => 'Metodo di pagamento',
		'type' => 'm',
		'icon' => 'fa-solid fa-cash-register',
		'headers' => [
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Predefinito', 'key' => 'predefinito', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['predefinito'] = $validatedData['predefinito'] == false ? 0 : 1;
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		$validatedData['predefinito'] = $validatedData['predefinito'] == false ? 0 : 1;
		if($validatedData['predefinito'] == 1) {
			$this->model::query()->update(['predefinito' => 0]);
		}
	}
	
	protected function setComponents()
	{
		return [
			'index' => 'metodi-pagamento/MetodiPagamentoIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'metodi-pagamento/MetodiPagamentoContent'
		];
	}
	
	protected function setJsonData(string $type, Model|Collection $object) 
	{
		$main = [
			'id' => $object->id,
			'nome' => $object->nome,
			'predefinito' => $object->predefinito
		];

		$data = match($type) {
			'show', 'edit' => [
				'giorni' => $object->giorni
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'giorni' => 'required',
				'predefinito' => 'required'
			],
			'store' => [
				'nome' => [
					'required',
					Rule::unique('metodi_pagamento', 'nome')->whereNull('deleted_at')
				]
			],
			'update' => [
				'nome' => [
					'required',
					Rule::unique('metodi_pagamento', 'nome')
						->ignore($object->id)
						->whereNull('deleted_at'),
				]
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'nome.unique' => "Il nome del metodo di pagamento deve essere unico.",
				'giorni.required' => 'Il campo giorni è obbligatorio.',
				'predefinito.required' => 'Il campo predefinito è obbligatorio.'
			]
		];
	}
}