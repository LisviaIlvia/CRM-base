<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;

class CategoryController extends AbstractCrudController
{
	protected string $pattern = 'categorie';
	protected string $model = Category::class;
	protected array $verifyDestroy = ['products'];
	
	protected array $indexSetup = [
		'plural' => 'Categorie',
		'single' => 'Categoria',
		'type' => 'f',
		'icon' => 'fa-solid fa-tag',
		'headers' => [
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Genitore', 'key' => 'genitore', 'sortable' => true],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];
	
	protected function beforeStore(&$validatedData)
	{
		if(!array_key_exists('genitore', $validatedData) || $validatedData['genitore'] == 0) {
			$validatedData['parent_id'] = null;
		} else {
			$validatedData['parent_id'] = $validatedData['genitore'];
		}
		unset($validatedData['genitore']);
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		if(!array_key_exists('genitore', $validatedData) || $validatedData['genitore'] == 0) {
			$validatedData['parent_id'] = null;
		} else {
			$validatedData['parent_id'] = $validatedData['genitore'];
		}
		unset($validatedData['genitore']);
	}
	
	protected function setJsonData(string $type, Model|Collection $object) 
	{
		$main = [
			'id' => $object->id,
			'nome' => $object->nome
		];

		$data = match($type) {
			'index', 'store', 'update' => [
				'genitore' => $object->parent?->nome ?? 'Nessuno'
			],
			'show', 'edit' => [
				'parent_id' => $object->parent_id
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setOtherData(string $type, Model $object) 
	{
		if($object && method_exists($object, 'children') && $object->children()->exists()) {
			$childrenIds = $object->children()->pluck('id')->toArray();
			$excludeIds = array_merge([$object->id], $childrenIds);
			$categorie = $this->model::whereNotIn('id', $excludeIds)->get();
		} else {
			$categorie = $this->model::all();
		}

		$categorie->prepend(['id' => 0, 'nome' => 'Nessuno']);
		
		return [
			'categorie' => $categorie
		];
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'genitore' => 'nullable'
			],
			'store' => [
				'nome' => [
					'required',
					Rule::unique('categories', 'nome')
					->whereNull('deleted_at')
				]
			],
			'update' => [
				'nome' => [
					'required',
					Rule::unique('categories', 'nome')
					->ignore($object->id)
					->whereNull('deleted_at')
				]
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'nome.unique' => "Il nome della categoria deve essere unico."
			]
		];
	}
}