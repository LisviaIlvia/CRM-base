<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ContoBancario;
use Illuminate\Validation\Rule;

class ContoBancarioController extends AbstractCrudController
{
	protected string $pattern = 'conti-bancari';
	protected string $model = ContoBancario::class;
	protected array $verifyDestroy = ['documents'];

	protected array $indexSetup = [
		'plural' => 'Conti bancari',
		'single' => 'Conto bancario',
		'type' => 'm',
		'icon' => 'fa-solid fa-building-columns',
		'headers' => [
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Predefinito', 'key' => 'predefinito', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];

	protected function beforeUpdate(&$validatedData)
	{
		if ($validatedData['Predefinito']) {
            $this->model::query()->update(['Predefinito' => 0]);
        }
	}

	protected function setComponents()
	{
		return [
			'index' => 'conti-bancari/ContiBancariIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'conti-bancari/ContiBancariContent'
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
				'iban' => $object->iban
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}

	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'iban' => 'required',
				'predefinito' => 'required'
			],
			'store' => [
				'nome' => [
					'required',
					Rule::unique('conti_bancari', 'nome')->whereNull('deleted_at')
				]
			],
			'update' => [
				'nome' => [
					'required',
					Rule::unique('conti_bancari', 'nome')
						->ignore($object->id)
						->whereNull('deleted_at'),
				]
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'nome.unique' => "Il nome del conto bancario deve essere unico.",
				'iban.required' => 'Il campo iban è obbligatorio.',
				'predefinito.required' => 'Il campo predefinito è obbligatorio.'
			]
		];
	}
}
