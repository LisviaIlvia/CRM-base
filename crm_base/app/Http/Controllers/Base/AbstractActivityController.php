<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Activity;
use App\Models\Entity;
use Carbon\Carbon;

abstract class AbstractActivityController extends AbstractCrudController 
{
	protected string $model = Activity::class;
	protected string $table_id = 'entity_id';
	
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
			'index' => 'attivita/AttivitaIndex',
			'create' => 'Crud/CrudCreate',
			'edit' => 'Crud/CrudEdit',
			'show' => 'Crud/CrudShow',
			'content' => 'attivita/AttivitaContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['entity_id'] = $validatedData['element_id'];
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$element_id = request()->input('element_id');
			
		$main = [
			'id' => $object->id,
			'type' => $object->type,
			'descrizione' => $object->descrizione
		];

		$data = match($type) {
			'index', 'store', 'update' => [
				'data' => Carbon::createFromFormat('Y-m-d', $object->data)->format('d/m/Y'),
			],
			'edit', 'show' => [
				'data' => Carbon::createFromFormat('Y-m-d', $object->data)
			],
			'create' => [
				'element_id' => $element_id
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setOtherData(string $type, Model $object) 
	{
		$types = ['Preventivo', 'Chiamata'];
		
		return ['types' => $types];
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'type' => 'required',
				'data' => 'required',
				'descrizione' => 'nullable'
			],
			'store' => [
				'element_id' => 'required'
			],
			'messages' => [
				'type.required' => 'Il campo type è obbligatorio.',
				'data.required' => 'Il campo data è obbligatorio.',
				'element_id.required' => 'Il campo element_id è obbligatorio.'
			]
		];
	}
}