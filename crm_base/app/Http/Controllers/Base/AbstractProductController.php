<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Helpers\FunctionsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;
use App\Models\AliquotaIva;
use App\Models\Category;
use Illuminate\Validation\Rule;

abstract class AbstractProductController extends AbstractCrudController
{
	protected string $prefix_code = '';
	protected string $model = Product::class;
	protected bool $rinnovabile = false;
	protected bool $giacenza = false;
	protected array $ricorrenze;
	protected array $verifyDestroy = ['documentProduct'];
	
	protected array $documents = [];
	
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
			'content' => 'ProductsContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['type'] = $this->pattern;

		if (isset($validatedData['prezzo_iva'])) {
			$validatedData['prezzo'] = $validatedData['prezzo_iva']['prezzo'] ;
			$validatedData['aliquota_iva_id'] = $validatedData['prezzo_iva']['aliquota_iva_id'];
			$validatedData['aliquota_iva_predefinita'] = $validatedData['prezzo_iva']['aliquota_iva_predefinita'];
			$validatedData['tax_in'] = $validatedData['prezzo_iva']['tax_in'];
		}
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		$validatedData['type'] = $this->pattern;

		if (isset($validatedData['prezzo_iva'])) {
			$validatedData['prezzo'] = $validatedData['prezzo_iva']['prezzo'] ;
			$validatedData['aliquota_iva_id'] = $validatedData['prezzo_iva']['aliquota_iva_id'];
			$validatedData['aliquota_iva_predefinita'] = $validatedData['prezzo_iva']['aliquota_iva_predefinita'];
			$validatedData['tax_in'] = $validatedData['prezzo_iva']['tax_in'];
		}
	}
	
	protected function afterStore(&$object, $validatedData)
	{
		$this->syncCategorieConGenitori($object, $validatedData);
	}
	
	protected function afterUpdate(&$object, $validatedData)
	{
		$this->syncCategorieConGenitori($object, $validatedData);
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$documents = [];
		$all_documents = $object->documents;
		
		if(count($this->documents) > 0) {
			foreach($this->documents as $key => $document) {
				if($key == 'magazzino') {
					$merce = new Collection($object);
					$documents[$key] = $document->indexProductFilter($merce);
				} else {
					$filtered_documents = $all_documents->where('type', $key)->values();
					$documents[$key] = $document->indexDocFilter($filtered_documents);
				}
			}
		}
		
		$main = [
			'id' => $object->id,
			'codice' => $object->codice,
			'nome' => $object->nome
		];

		$data = match($type) {
			'create' => [
				'codice' => FunctionsHelper::getLastCode($this->prefix_code, $this->model)
			],
			'show' => array_merge($this->getShowEditFields($object), [
				'documents' => $documents
			]),
			'edit' => $this->getShowEditFields($object),
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}
	
	protected function setOtherData(string $type, Model $object) 
	{
		$aliquote_iva = AliquotaIva::orderBy('aliquota')->get();
		$categorie = Category::with('children')->get();
		$categorieFiltrate = collect();

		foreach ($categorie as $categoria) {
			if ($categoria->children->isEmpty()) {
				$categorieFiltrate->push($categoria);
			}
		}

		$categorieFiltrate = $categorieFiltrate->sortBy('nome')->values();
		$categorieFiltrateIds = $categorieFiltrate->pluck('id');
		
		if($object && method_exists($object, 'categories')) {
			$categorieSelezionate = $object->categories->pluck('id')->filter(function ($id) use ($categorieFiltrateIds) {
				return $categorieFiltrateIds->contains($id);
			})->values()->all();
		} else {
			$categorieSelezionate = [];
		}
		
		return [
			'aliquote_iva' => $aliquote_iva,
			'categorie' => $categorieFiltrate,
			'categorie_selezionate' => $categorieSelezionate,
			'ricorrenze' => $this->ricorrenze,
			'giacenza' => $this->giacenza,
			'rinnovabile' => [
				'active' => $this->rinnovabile,
				'text' => strtolower($this->indexSetup['single'])
			]
		];
	}
	
	protected function setValidation(Model $object) 
	{	
		return [
			'rules' => [
				'prezzo_iva.prezzo' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
				'prezzo_iva.aliquota_iva_id' => 'required',
				'prezzo_iva.aliquota_iva_predefinita' => 'required',
				'prezzo_iva.tax_in' => 'required',
				'descrizione' => 'nullable',
				'unita_misura' => 'required',
				'categorie' => 'nullable',
				'ricorrenza' => 'required',
				'rinnovabile' => 'nullable',
				'giacenza_iniziale' => 'nullable' 
			],
			'store' => [
				'codice' => [
					'required',
					Rule::unique('products', 'codice')
					->whereNull('deleted_at')
				],
				'nome' => [
					'required',
					Rule::unique('products', 'nome')
					->whereNull('deleted_at')
				]
			],
			'update' => [
				'codice' => [
					'required',
					Rule::unique('products', 'codice')
					->ignore($object->id)
					->whereNull('deleted_at')
				],
				'nome' => [
					'required',
					Rule::unique('products', 'nome')
					->ignore($object->id)
					->whereNull('deleted_at')
				]
			],
			'messages' => [
				'codice.required' => 'Il codice è obbligatorio.',
				'codice.unique' => 'Il codice inserito è già in uso.',
				'nome.required' => 'Il campo nome è obbligatorio.',
				'nome.unique' => "Il nome della merce deve essere unico.",
				'unita_misura.required' => 'Il campo unità di misura è obbligatorio.',
				'prezzo_iva.prezzo.required' => 'Il campo prezzo è obbligatorio.',
				'prezzo_iva.prezzo.numeric' => 'Il prezzo deve essere un numero.',
				'prezzo_iva.prezzo.min' => 'Il prezzo non può essere negativo.',
				'prezzo_iva.prezzo.regex' => 'Il prezzo deve essere un valore decimale con massimo due cifre decimali.',
				'prezzo_iva.aliquota_iva_id.required' => 'Il campo aliquota iva è obbligatorio.',
				'prezzo_iva.aliquota_iva_predefinita.required' => 'Il campo aliquota iva predefinita è obbligatorio.',
				'prezzo_iva.tax_in.required' => 'Il campo tassazione inclusa è obbligatorio.',
				'ricorrenza.required' => 'La ricorrenza è obbligatoria.',
			]
		];
	}
	
	private function getShowEditFields(Model $object)
	{
		return [
			'descrizione' => $object->descrizione,
			'unita_misura' => $object->unita_misura,
			'prezzo_iva' => [
				'prezzo' => $object->prezzo,
				'aliquota_iva_id' => $object->aliquota_iva_id,
				'aliquota_iva_predefinita' => $object->aliquota_iva_predefinita,
				'tax_in' => $object->tax_in
			],
			'ricorrenza' => $object->ricorrenza,
			'rinnovabile' => $object->rinnovabile,
			'giacenza_iniziale' => $object->giacenza_iniziale
		];
	}
	
	private function getGenitoriCategoria($categoriaId) {
		$genitori = [];
		$categoriaCorrente = Category::find($categoriaId);

		while ($categoriaCorrente && $categoriaCorrente->parent_id !== null) {
			$categoriaCorrente = $categoriaCorrente->parent;
			if ($categoriaCorrente) {
				$genitori[] = $categoriaCorrente->id;
			}
		}

		return $genitori;
	}
	
	private function syncCategorieConGenitori(&$object, $validatedData)
	{
		$categorieIds = $validatedData['categorie'] ?? [];
		$categorieConGenitori = [];

		foreach ($categorieIds as $categoriaId) {
			$categorieConGenitori = array_merge($categorieConGenitori, $this->getGenitoriCategoria($categoriaId));
		}

		$categorieConGenitori = array_unique(array_merge($categorieIds, $categorieConGenitori));

		if ($object->exists) {
			$object->categories()->sync($categorieConGenitori);
		}
	}
}