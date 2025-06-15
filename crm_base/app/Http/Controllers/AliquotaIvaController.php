<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\AliquotaIva;
use Illuminate\Validation\Rule;

class AliquotaIvaController extends AbstractCrudController
{
	protected string $pattern = 'aliquote-iva';
	protected string $model = AliquotaIva::class;
	//protected array $verifyDestroy = ['products', 'documentProduct', 'documentAltro', 'spedizioni'];

	protected array $indexSetup = [
		'plural' => 'Aliquote IVA',
		'single' => 'Aliquota IVA',
		'type' => 'a',
		'icon' => 'fa-solid fa-percent',
		'order' => [ 'key' => 'aliquota', 'order' => 'asc' ],
		'headers' => [
			['title' => 'Aliquota', 'key' => 'aliquota', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Predefinita', 'key' => 'predefinita', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];

	protected function beforeUpdate(&$validatedData)
	{
		if ($validatedData['predefinita']) {
            $this->model::query()->update(['predefinita' => 0]);
        }
	}

	protected function setComponents()
	{
		return [
			'index' => 'aliquote-iva/AliquoteIvaIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'aliquote-iva/AliquoteIvaContent'
		];
	}

	protected function setJsonData(string $type, Model|Collection $object)
	{
		$main = [
			'id' => $object->id,
			'nome' => $object->nome,
			'predefinita' => $object->predefinita
		];

		$data = match($type) {
			'index', 'store', 'update' => [
				'aliquota' => number_format($object->aliquota ?? 0, 2, ',', '')
			],
			'show', 'edit' => [
				'aliquota' => $object->aliquota ?? 0,
				'descrizione' => $object->descrizione ?? ''
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}

	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'nome' => 'nullable',
				'descrizione' => 'nullable',
				'predefinita' => 'required'
			],
			'store' => [
				'aliquota' => [
					'required',
					Rule::unique('aliquote_iva')->where(function ($query) {
						return $query->where('aliquota', request()->aliquota)
							->where('nome', request()->nome)
							->whereNull('deleted_at');
					})
				]
			],
			'update' => [
				'aliquota' => [
					'required',
					Rule::unique('aliquote_iva')->where(function ($query) use ($object) {
						return $query->where('aliquota', request()->aliquota)
							->where('nome', request()->nome)
							->whereNull('deleted_at');
					})->ignore($object->id)
				]
			],
			'messages' => [
				'aliquota.required' => 'Il campo aliquota è obbligatorio.',
				'aliquota.unique' => "Il aliquota deve essere unico.",
				'predefinita.required' => 'Il campo predefinita è obbligatorio.'
			]
		];
	}
}
