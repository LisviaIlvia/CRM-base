<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\FunctionsHelper;
use App\Models\Document;
use App\Models\DocumentIndirizzo;
use App\Models\DocumentProduct;
use App\Models\DocumentAltro;
use App\Models\DocumentDescrizione;
use App\Models\DocumentSpedizione;
use App\Models\DocumentTrasporto;
use App\Models\DocumentRata;
use App\Models\AliquotaIva;
use App\Models\Entity;
use App\Models\Product;
use App\Models\MetodoPagamento;
use App\Models\ContoBancario;
use App\Models\Spedizione;

use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Unit;

abstract class AbstractDocumentController extends AbstractCrudController 
{
	protected string $prefix_code = '';
	protected bool $entrata =  false;
	protected string $pattern;
	protected array $intestatari;
	protected array $tipi_intestatari;
	protected string $model = Document::class;
	protected bool $spedizione_active = false;
	protected bool $trasporto_active = false;
	protected bool $metodo_pagamento_active = false;
	protected bool $rate_active = false;
	protected bool $activeYear = true;
	protected array $stati = ['Aperto', 'Chiuso'];
	protected string $stato_iniziale = 'Aperto';
	protected array $types_relation = [];
	
	protected array $dialogSetup = [
		'create' => [
			'width' => '100%',
			'fullscreen' => true,
			'scrim' => false
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
	
	public function indexDocFilter(Collection $collection)
	{
		return $this->getPropsIndex($collection);
	}
	
	protected function getCollectionIndex() 
	{
		return $this->model::getDocuments($this->pattern)->get();
	}
	
	protected function setComponents()
	{
		return [
			'index' => 'Crud/CrudIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'documents/DocumentsShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'documents/DocumentsContent'
		];
	}
	
	protected function beforeStore(&$validatedData)
	{
		$validatedData['type'] =  $this->pattern;
		
		if(isset($validatedData['metodo_pagamento_id'])) {
			if($validatedData['metodo_pagamento_id'] == '0' || $validatedData['metodo_pagamento_id'] == null) unset($validatedData['metodo_pagamento_id']);
		}

		if(isset($validatedData['conto_bancario_id'])) {
			if($validatedData['conto_bancario_id'] == '0' || $validatedData['conto_bancario_id'] == null) unset($validatedData['conto_bancario_id']);
		}		
		
	}
	
	protected function afterStore(&$object, $validatedData)
	{
		DocumentIndirizzo::create(array_merge(['document_id' => $object->id], $validatedData['indirizzo']));
		
		if(array_key_exists('rate', $validatedData) && is_array($validatedData['rate']) && !empty($validatedData['rate'])) {
			foreach ($validatedData['rate'] as $rata) {
				$object->rate()->create([
					'data' => isset($rata['data']) ? $rata['data'] : $validatedData['data'],
					'percentuale' => $rata['percentuale'],
					'importo' => $rata['importo']
				]);
			}
		}
		
		if (array_key_exists('allegati', $validatedData) && is_array($validatedData['allegati']) && !empty($validatedData['allegati'])) {
			$pattern_name = $this->pattern;
			
			$files = request()->file('allegati');
			foreach ($files as $index => $value) {
				$file = $value['file'];
				$extension = $file->getClientOriginalExtension();
				$filename = $pattern_name . '-num' . $validatedData['numero'] . '-' . $validatedData['data'] . '-' . $index . '.' . $extension;
				$file->storeAs('private/media/' . $pattern_name, $filename);
				
				$object->media()->create([
					'name' => $filename,
					'extension' => $file->getClientOriginalExtension(),
					'mime_type' => $file->getClientMimeType(),
					'url' => '/media/' . $pattern_name . '/' . $filename,
					'relationable_id' => $object->id,
					'relationable_type' => get_class($object)
				]);
			}
		}
		
		if (!empty($validatedData['elementi'])) {
			foreach ($validatedData['elementi'] as $key => $element) {
				
				switch($element['tipo']) {
					case 'merci':
					case 'servizi':
						DocumentProduct::create([
							'document_id' => $object->id,
							'product_id' => request()->elementi[$key]['id'],
							'type' =>  $element['tipo'],
							'quantita' => $element['quantita'],
							'prezzo' => $element['prezzo'],
							'tipo_sconto' => $element['tipo_sconto'],
							'sconto' => $element['sconto'],
							'aliquota_iva_id' => $element['iva']['aliquota_iva_id'],
							'ricorrenza' => request()->elementi[$key]['ricorrenza'],
							'order' => $key
						]);
						break;
					case 'altro':
						DocumentAltro::create([
							'document_id' => $object->id,
							'nome' => $element['nome'],
							'quantita' => $element['quantita'],
							'unita_misura' => request()->elementi[$key]['unita_misura'],
							'prezzo' => $element['prezzo'],
							'sconto' => $element['sconto'],
							'tipo_sconto' => $element['tipo_sconto'],
							'aliquota_iva_id' => $element['iva']['aliquota_iva_id'],
							'ricorrenza' => request()->elementi[$key]['ricorrenza'],
							'order' => $key
						]);
						break;
					case 'descrizione':
						DocumentDescrizione::create([
							'document_id' => $object->id,
							'descrizione' => $element['descrizione'],
							'order' => $key
						]);
						break;
				}
			}	
		}
		
		if($this->spedizione_active === true) {	
			if (isset($validatedData['spedizione']) && !empty($validatedData['spedizione'])) {
				if($validatedData['spedizione']['spedizione_id'] != 0) {
					DocumentSpedizione::create([
						'document_id' => $object->id,
						'prezzo' => $validatedData['spedizione']['prezzo'],
						'sconto' => $validatedData['spedizione']['sconto'],
						'spedizione_id' => $validatedData['spedizione']['spedizione_id'],
						'aliquota_iva_id' => $validatedData['spedizione']['iva']['aliquota_iva_id']
					
					]);
				}
			}
		}
		
		if($this->trasporto_active === true) {
			if(isset($validatedData['trasporto']) && !empty($validatedData['trasporto'])) {
				DocumentTrasporto::create([
					'document_id' => $object->id,
					'colli' => $validatedData['trasporto']['colli'] ?? null,
					'peso' => $validatedData['trasporto']['peso'] ?? null,
					'causale' => $validatedData['trasporto']['causale'] ?? null,
					'porto' => $validatedData['trasporto']['porto'] ?? null,
					'a_cura' => $validatedData['trasporto']['a_cura'] ?? null,
					'vettore' => $validatedData['trasporto']['vettore'] ?? null,
					'annotazioni' => $validatedData['trasporto']['annotazioni'] ?? null
				]);
			}
		}
	}
	
	protected function beforeUpdate(&$validatedData)
	{
		$validatedData['type'] =  $this->pattern;
		
		if(isset($validatedData['metodo_pagamento_id'])) {
			if($validatedData['metodo_pagamento_id'] == '0' || $validatedData['metodo_pagamento_id'] == null) unset($validatedData['metodo_pagamento_id']);
		}

		if(isset($validatedData['conto_bancario_id'])) {
			if($validatedData['conto_bancario_id'] == '0' || $validatedData['conto_bancario_id'] == null) unset($validatedData['conto_bancario_id']);
		}		
		
	}
	
	protected function afterUpdate(&$object, $validatedData)
	{
		$object->indirizzo->update($validatedData['indirizzo']);
		
		if (array_key_exists('rate', $validatedData) && is_array($validatedData['rate']) && !empty($validatedData['rate'])) {
			$existingRate = $object->rate;
			$newRate = collect($validatedData['rate']);
			
			foreach ($existingRate as $existingRata) {
				$matchingRate = $newRate->first(function ($newRata) use ($existingRata) {
					return $newRata['data'] === $existingRata->data &&
						   $newRata['percentuale'] == $existingRata->percentuale &&
						   $newRata['importo'] == $existingRata->importo;
				});


				if (!$matchingRate) {
					$existingRata->delete();
				} else {
					$newRate = $newRate->reject(function ($newRata) use ($matchingRate) {
						return $newRata['data'] === $matchingRate['data'] &&
							   $newRata['percentuale'] == $matchingRate['percentuale'] &&
							   $newRata['importo'] == $matchingRate['importo'];
					});
				}
			}
			
			foreach ($newRate as $newRata) {
				$object->rate()->create([
					'percentuale' => $newRata['percentuale'],
					'importo' => $newRata['importo'],
					'data' => isset($newRata['data']) ? $newRata['data'] : $validatedData['data']
				]);
			}
		}
		
		if (array_key_exists('allegati', $validatedData) && is_array($validatedData['allegati']) && !empty($validatedData['allegati'])) {
			$pattern_name = $this->pattern;
			
			$allegatiIds = array_column($validatedData['allegati'], 'id');
			foreach ($object->media as $mediaItem) {
				if (!in_array($mediaItem->id, $allegatiIds)) {
					Storage::delete('private/media/' . $pattern_name . '/' . $mediaItem->name);
					$mediaItem->delete();
				}
			}

			$files = request()->file('allegati');
			
			if(!empty($files)) {
				foreach ($files as $index => $value) {
					$file = $value['file'];
					$extension = $file->getClientOriginalExtension();
					$filename = $pattern_name . '-num' . $validatedData['numero'] . '-' . $validatedData['data'] . '-' . $index . '.' . $extension;
					$file->storeAs('private/media/' . $pattern_name, $filename);

					$object->media()->create([
						'name' => $filename,
						'extension' => $extension,
						'mime_type' => $file->getClientMimeType(),
						'url' => '/media/' . $pattern_name . '/' . $filename,
						'relationable_id' => $object->id,
						'relationable_type' => get_class($object)
					]);
				}
			}
		} elseif ($object->media && empty(request()->allegati)) {
			foreach ($object->media as $mediaItem) {
				Storage::delete('private/media/' . $pattern_name . '/' . $mediaItem->name);
				$mediaItem->delete();
			}
		} else {
			$existingMediaIds = $object->media->pluck('id')->toArray();
			$newMediaIds = array_column($validatedData['allegati'], 'id');

			$mediaIdsToDelete = array_diff($existingMediaIds, $newMediaIds);
			foreach ($mediaIdsToDelete as $mediaId) {
				$mediaItem = $object->media()->find($mediaId);
				Storage::delete('private/media/' . $pattern_name . '/' . $mediaItem->name);
				$mediaItem->delete();
			}
		}
		
		if (!empty($validatedData['elementi'])) {
			
			$existingProductsIds = $object->products()->pluck('id')->toArray();
			$existingAltroIds = $object->altro()->pluck('id')->toArray();
			$existingDescrizioniIds = $object->descrizioni()->pluck('id')->toArray();
			
			$receivedProductsIds = array_filter(array_map(function ($item) {
				return (($item['tipo'] === 'merci' || $item['tipo'] === 'servizi') && isset($item['element_id'])) ? $item['element_id'] : null;
			}, request()->elementi));

			$receivedAltroIds = array_filter(array_map(function ($item) {
				return ($item['tipo'] === 'altro' && isset($item['element_id'])) ? $item['element_id'] : null;
			}, $validatedData['elementi']));

			$receivedDescrizioniIds = array_filter(array_map(function ($item) {
				return ($item['tipo'] === 'descrizione' && isset($item['element_id'])) ? $item['element_id'] : null;
			}, $validatedData['elementi']));

			$productsIdsToDelete = array_diff($existingProductsIds, $receivedProductsIds);
			$altroIdsToDelete = array_diff($existingAltroIds, $receivedAltroIds);
			$descrizioniIdsToDelete = array_diff($existingDescrizioniIds, $receivedDescrizioniIds);

			DocumentProduct::destroy($productsIdsToDelete);
			DocumentAltro::destroy($altroIdsToDelete);
			DocumentDescrizione::destroy($descrizioniIdsToDelete);
			
			foreach($validatedData['elementi'] as $key => $element) {
				switch($element['tipo']) {
					case 'merci':
					case 'servizi':
						$data = [
							'document_id' => $object->id,
							'quantita' => $element['quantita'],
							'prezzo' => $element['prezzo'],
							'tipo_sconto' => $element['tipo_sconto'],
							'sconto' => $element['sconto'],
							'product_id' => request()->elementi[$key]['id'],
							'aliquota_iva_id' => $element['iva']['aliquota_iva_id'],
							'ricorrenza' => request()->elementi[$key]['ricorrenza'],
							'order' => $key
						];
					
						if(isset(request()->elementi[$key]['element_id'])) {
							$product = $object->products()->find(request()->elementi[$key]['element_id']);
							$product->document_id = $data['document_id'];
							$product->quantita = $data['quantita'];
							$product->prezzo = $data['prezzo'];
							$product->tipo_sconto = $data['tipo_sconto'];
							$product->sconto = $data['sconto'];
							$product->product_id = $data['product_id'];
							$product->aliquota_iva_id = $data['aliquota_iva_id'];
							$product->ricorrenza = $data['ricorrenza'];
							$product->order = $data['order'];
							$product->save();
						} else {
							DocumentProduct::create($data);
						}
						break;
					case 'altro':
						$data = [
							'document_id' => $object->id,
							'nome' => $element['nome'],
							'quantita' => $element['quantita'],
							'unita_misura' => request()->elementi[$key]['unita_misura'],
							'prezzo' => $element['prezzo'],
							'tipo_sconto' => $element['tipo_sconto'],
							'sconto' => $element['sconto'],
							'aliquota_iva_id' => $element['iva']['aliquota_iva_id'],
							'ricorrenza' => request()->elementi[$key]['ricorrenza'],
							'order' => $key
						];
					
						if(isset($element['element_id'])) {
							$altro = $object->altro()->find($element['element_id']);
							$altro->document_id = $data['document_id'];
							$altro->nome = $data['nome'];
							$altro->quantita = $data['quantita'];
							$altro->unita_misura = $data['unita_misura'];
							$altro->prezzo = $data['prezzo'];
							$altro->tipo_sconto = $data['tipo_sconto'];
							$altro->sconto = $data['sconto'];
							$altro->aliquota_iva_id = $data['aliquota_iva_id'];
							$altro->ricorrenza = $data['ricorrenza'];
							$altro->order = $data['order'];
							$altro->save();
						} else {
							DocumentAltro::create($data);
						}
						break;
					case 'descrizione':
						$data = [
							'document_id' => $object->id,
							'descrizione' => $element['descrizione'],
							'order' => $key
						];
					
						if(isset($elemento['element_id'])) {
							$descrizione = $object->descrizioni()->find($element['element_id']);
							$descrizione->document_id = $data['document_id'];
							$descrizione->descrizione = $data['descrizione'];
							$descrizione->order = $data['order'];
							$descrizione->save();
						} else {
							DocumentDescrizione::create($data);
						}
						break;
				}
			}	
		}
		
		if($this->spedizione_active === true) {
			if (isset($validatedData['spedizione']) && !empty($validatedData['spedizione'])) {
				if($validatedData['spedizione']['spedizione_id'] != 0) {
					$data = [
						'document_id' => $object->id,
						'prezzo' => $validatedData['spedizione']['prezzo'],
						'sconto' => $validatedData['spedizione']['sconto'],
						'spedizione_id' => $validatedData['spedizione']['spedizione_id'],
						'aliquota_iva_id' => $validatedData['spedizione']['iva']['aliquota_iva_id']
					
					];
					$object->spedizione()->updateOrCreate(
						['document_id' => $object->id],
						$data
					);
				} else {
					$object->spedizione()->where('document_id', $object->id)->delete();
				}
			}
		}
		
		if($this->trasporto_active === true) {
			if(isset($validatedData['trasporto']) && !empty($validatedData['trasporto'])) {
				DocumentTrasporto::create([
					'document_id' => $object->id,
					'colli' => $validatedData['trasporto']['colli'] ?? null,
					'peso' => $validatedData['trasporto']['peso'] ?? null,
					'causale' => $validatedData['trasporto']['causale'] ?? null,
					'porto' => $validatedData['trasporto']['porto'] ?? null,
					'a_cura' => $validatedData['trasporto']['a_cura'] ?? null,
					'vettore' => $validatedData['trasporto']['vettore'] ?? null,
					'annotazioni' => $validatedData['trasporto']['annotazioni'] ?? null
				]);
			}
		}
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		$main = [
			'id' => $object->id,
			'numero' => $object->numero,
			'stato' => $object->stato
		];

		$data = match($type) {
			'index', 'store', 'update', 'clone' => array_merge(
				$this->getEntityName($object),
				[
					'data' => Carbon::createFromFormat('Y-m-d', $object->data)->format('d/m/Y'),
					'imponibile' => number_format(FunctionsHelper::calculateImponibile($object), 2, ',', '') . ' €'
				]
			),
			'create' => array_merge($this->entrata === false ? FunctionsHelper::getLastNumber($this->prefix_code, $this->model) : [],
				[
					'interlocutore' => $this->entrata === true ? 'Mittente' : 'Destinatario',
					'intestatari' => $this->getIntestatari()
				]
			),
			'show', 'edit' => array_merge(FunctionsHelper::getRangeData($this->model, $object->data),[
				'document' => [
					'data' => Carbon::createFromFormat('Y-m-d', $object->data),
					'entity_id' => $object->entity_id,
					'entity_type' => $object->entity->type,
					'indirizzo' => $this->getIndirizzo($object->indirizzo),
					'allegati' => $object->media,
					'spedizione' => $this->getSpedizione($object),
					'trasporto' => $this->getTrasporto($object),
					'rate' => $this->getRate($object),
					'metodo_pagamento_id' => $object->metodoPagamento->id ?? 0,
					'conto_bancario_id' => $object->contoBancario->id ?? 0,
					'elementi' => FunctionsHelper::createElements($object),
					'note' => $object->note
				],
				'relation' => $this->getRelation($object),
				'interlocutore' => $this->entrata === true ? 'Mittente' : 'Destinatario',
				'intestatari' => $this->getIntestatari(),
				'magic_url' => count($this->types_relation) > 0 ? $this->getAction($object, ['magic' => 'magic', 'magicsync' => 'magic']) : null,
				'pdf_url' => $this->pdf === true ? $this->getAction($object, ['pdf' => 'pdf']) : null
			]),
			default => [],
		};

		return array_merge(['main' => $main], [$type => $data]);
	}

	protected function setOtherData(string $type, Model $object) 
	{	
		$data['aliquote_iva'] = AliquotaIva::all();
		$data['tipi_intestatari'] = $this->tipi_intestatari;
		
		$data['prodotti']['merci'] = Product::merci()->with(['categories', 'aliquotaIva'])->get();
		if($this->trasporto_active === false) $data['prodotti']['servizi'] = Product::servizi()->with(['categories', 'aliquotaIva'])->get();
		
		$data['ricorrenze'] = [
			[
				'value' => 'una_tantum',
				'title'=> 'Una tantum'
			],
			[
				'value' => 'mensile',
				'title'=> 'Mensile'
			],
			[
				'value' => 'trimestrale',
				'title'=> 'Trimestrale'
			],
			[
				'value' => 'semestrale',
				'title'=> 'Semestrale'
			],
			[
				'value' => 'annuale',
				'title'=> 'Annuale'
			]
		];
		
		$metodi_pagamento = MetodoPagamento::all();
		$metodi_pagamento->prepend([
			'id' => 0,
			'nome' => 'Nessuno'
		]);
		$data['metodi_pagamento'] = $metodi_pagamento;
		
		$conti_bancari = ContoBancario::all();
		$conti_bancari->prepend([
			'id' => 0,
			'nome' => 'Nessuno'
		]);
		$data['conti_bancari'] = $conti_bancari;
		
		$spedizioni = Spedizione::with(['aliquotaIva'])->get();
		$spedizioni->prepend([
			'id' => 0,
			'nome' => 'Nessuna',
			'aliquotaIva' => null
		]);
		
		$data['spedizioni'] = $spedizioni;
		$data['entrata'] = $this->entrata;
		$data['spedizione_active'] = $this->spedizione_active;
		$data['trasporto_active'] = $this->trasporto_active;
		$data['metodo_pagamento_active'] = $this->metodo_pagamento_active;
		$data['rate_active'] = $this->rate_active;
		$data['stati'] = $this->stati;
		$data['types_relation'] = $this->types_relation;
		
		return $data;
	}
	
	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'stato' => 'nullable',
				'data' => 'required',
				'allegati' => 'nullable|array',
				'note' => 'nullable|string',
				'entity_id' => 'required|integer',
				'indirizzo' => 'array|required',
				'rate' => 'nullable|array',
				'elementi' => 'array|required',
				'elementi.*.nome' => 'required_unless:elementi.*.tipo,descrizione|string',
				'elementi.*.descrizione' => 'required_unless:elementi.*.tipo,altro,merci,servizi|string',
				'elementi.*.quantita' => 'required_unless:elementi.*.tipo,descrizione|numeric|min:1',
				'elementi.*.tipo_sconto' => 'required_unless:elementi.*.tipo,descrizione',
				'elementi.*.sconto' => 'required_unless:elementi.*.tipo,descrizione',
				'elementi.*.iva' => 'required_unless:elementi.*.tipo,descrizione',
				'elementi.*.tipo' => 'required|in:descrizione,altro,merci,servizi',
				'elementi.*.prezzo' => 'required_unless:elementi.*.tipo,descrizione|numeric',
				'spedizione' => 'nullable',
				'trasporto' => 'nullable',
				'metodo_pagamento_id' => 'nullable|integer',
				'conto_bancario_id' => 'nullable|integer'
			],
			'store' => [
				'numero' => [
					'required',
					'string',
					'max:255',
					Rule::unique('documents', 'numero')
					->whereNull('deleted_at')
				]
			],
			'update' => [
				'numero' => [
					'required',
					'string',
					'max:255',
					Rule::unique('documents', 'numero')
					->ignore($object->id)
					->whereNull('deleted_at')
				]
			],
			'messages' => [
				'numero.required' => 'Il campo numero è obbligatorio.',
				'numero.unique' => 'Il campo numero inserito è già in uso.',
				'data.required' => 'Il campo data è obbligatorio.',
				'entity_id.required' => 'Il campo tipo è obbligatorio.',
				'indirizzo.required' => 'Devi aggiungere almeno un indirizzo.',
				'elementi.required' => 'Devi aggiungere almeno un elemento.',
				'elementi.*.nome.required_unless' => 'Il nome degli elementi è obbligatorio.',
				'elementi.*.quantita.required_unless' => 'La quantità degli elementi è obbligatoria.',
				'elementi.*.descrizione.required_unless' => 'La descrizione degli elementi è obbligatoria.',
				'elementi.*.tipo.required' => 'Il tipo degli elementi è obbligatorio.',
				'elementi.*.prezzo.required_unless' => 'Il prezzo degli elementi è obbligatorio.'
			]
		];
	}
	
	protected function getJsonData(string $type, Model|Collection $object = null, bool $create = false)
	{
		$object = $object ?? new $this->model;
        $jsonData = $this->setJsonData($type, $object) ?? [];
        
        $main = $jsonData['main'] ?? [];
        
		$typeMap = [
			'index' => $jsonData['index'] ?? [],
			'create' => $jsonData['create'] ?? [],
			'show' => $jsonData['show'] ?? [],
			'edit' => $jsonData['edit'] ?? [],
			'store' => $jsonData['store'] ?? [],
			'update' => $jsonData['update'] ?? [],
			'clone' => $jsonData['clone'] ?? [],
			'destroy' => $jsonData['destroy'] ?? []
		];
		
		$data = $typeMap[$type] ?? [];
		
		if ($object->exists && in_array($type, ['store', 'update', 'clone'])) {
			$data['actions'] = $this->getAction($object, [
				'show' => 'show', 
				'edit' => 'edit', 
				'update' => 'edit', 
				'destroy' => 'delete',
				'clone' => 'clone',
				'pdf' => 'pdf'
			]);
		}

        return array_merge((array) $main, (array) $data);
	}
	
	protected function setClone(Model $object)
	{
		$newDocument = $object->replicate();
		
		$year = Carbon::parse($object->data)->year;
		
		$newDocument->numero = FunctionsHelper::getLastNumber($this->prefix_code, $this->model, $year)['numero'];
        $newDocument->data = Carbon::now()->toDateString();
        $newDocument->parent_id = $object->id;
        $newDocument->stato = $this->stato_iniziale;
        $newDocument->save();
		
		if ($object->indirizzo) {
            $newDocument->indirizzo()->create($object->indirizzo->toArray());
        }
		
		foreach ($object->products as $product) {
            $newDocument->products()->create($product->toArray());
        }
		
		foreach ($object->altro as $altro) {
            $newDocument->altro()->create($altro->toArray());
        }

        foreach ($object->descrizioni as $descrizione) {
            $newDocument->descrizioni()->create($descrizione->toArray());
        }

        if ($this->spedizione_active && $object->spedizione) {
            $newDocument->spedizione()->create($object->spedizione->toArray());
        }

        if ($this->trasporto_active && $object->trasporto) {
            $newDocument->trasporto()->create($object->trasporto->toArray());
        }
		
		return $newDocument;
	}
	
	public function magic($id)
	{
		$document = $this->resolveModel($id);

		$missingRate = $document->rate;

		$relation = $this->getRelation($document);
		$parents = $relation['parents'];
		$children = $relation['children'];

		$children_collection = $document->children()->with(['products', 'altro'])->get();

		$combineQuantitiesAndTotals = function ($items, $key, $quantityKey, $priceKey, $discountKey, $discountTypeKey) {
			return collect($items)
				->groupBy($key)
				->map(function ($group) use ($quantityKey, $priceKey, $discountKey, $discountTypeKey) {
					$quantitySum = $group->sum($quantityKey);
					$totalSum = $group->sum(function ($item) use ($quantityKey, $priceKey, $discountKey, $discountTypeKey) {
						$price = $item[$priceKey] ?? 0;
						$discount = $item[$discountKey] ?? 0;
						$discountType = $item[$discountTypeKey] ?? null;

						if ($discountType === '%') {
							$price -= $price * ($discount / 100);
						} elseif ($discountType === '€') {
							$price -= $discount;
						}

						return max(0, $price) * ($item[$quantityKey] ?? 0);
					});
					return [
						'quantity' => $quantitySum,
						'total' => $totalSum
					];
				});
		};

		$compareElements = function ($mainElements, $combinedQuantitiesAndTotals, $key, $quantityKey, $priceKey, $discountKey, $discountTypeKey) {
			return collect($mainElements)->map(function ($element) use ($combinedQuantitiesAndTotals, $key, $quantityKey, $priceKey, $discountKey, $discountTypeKey) {
				$combined = $combinedQuantitiesAndTotals->get($element[$key], ['quantity' => 0, 'total' => 0]);
				$combinedQuantity = $combined['quantity'];
				$combinedTotal = $combined['total'];

				$mainQuantity = $element[$quantityKey] ?? 0;
				$mainPrice = $element[$priceKey] ?? 0;
				$mainDiscount = $element[$discountKey] ?? 0;
				$mainDiscountType = $element[$discountTypeKey] ?? null;
				

				if ($mainDiscountType === '%') {
					$mainPriceDiscount = $mainPrice - ( $mainPrice * ($mainDiscount / 100) );
				} elseif ($mainDiscountType === '€') {
					$mainPriceDiscount = $mainPrice - $mainDiscount;
				}

				$mainTotal = max(0, $mainPriceDiscount) * $mainQuantity;

				$remainingTotal = max(0, $mainTotal - $combinedTotal);
				$remainingQuantity = ($remainingTotal > 0) ? (int) ceil($remainingTotal / $mainPriceDiscount) : max(0, $mainQuantity - $combinedQuantity);

				if ($remainingQuantity > 0 || $remainingTotal > 0) {
					$elementArray = is_array($element) ? $element : $element->toArray();
					$elementArray[$quantityKey] = $remainingQuantity;
					$elementArray['remaining_total'] = $remainingTotal;
					$elementArray['importo'] = $remainingTotal;
					if($remainingQuantity == 1) $elementArray['prezzo'] = $mainPriceDiscount;

					if ($element instanceof DocumentProduct && $element->product) {
						$elementArray['nome'] = $element->product->nome;
					}

					return $elementArray;
				}

				return null;
			})->filter();
		};
		
		$combinedProductQuantitiesAndTotals = $children_collection->flatMap(function ($child) {
			return collect($child['products'] ?? []);
		})->pipe(function ($products) use ($combineQuantitiesAndTotals) {
			return $combineQuantitiesAndTotals($products, 'product_id', 'quantita', 'prezzo', 'sconto', 'tipo_sconto');
		});

		$combinedAltroQuantitiesAndTotals = $children_collection->flatMap(function ($child) {
			return collect($child['altro'] ?? []);
		})->pipe(function ($altro) use ($combineQuantitiesAndTotals) {
			return $combineQuantitiesAndTotals($altro, 'nome', 'quantita', 'prezzo', 'sconto', 'tipo_sconto');
		});

		$missingProducts = $compareElements(
			$document->products ?? [],
			$combinedProductQuantitiesAndTotals,
			'product_id',
			'quantita',
			'prezzo',
			'sconto',
			'tipo_sconto'
		)->values()->toArray();

		$missingAltro = $compareElements(
			$document->altro ?? [],
			$combinedAltroQuantitiesAndTotals,
			'nome',
			'quantita',
			'prezzo',
			'sconto',
			'tipo_sconto'
		)->values()->toArray();

		return response()->json([
			'parents' => $parents,
			'children' => $children,
			'missingProducts' => $missingProducts,
			'missingAltro' => $missingAltro,
			'missingRate' => $missingRate
		]);
	}
	
	public function magicSync($id) 
	{	
		$validatedData = request()->validate(
			[
				'document_type' => 'required',
				'year' => 'required',
				'altro' => 'nullable',
				'prodotti' => 'nullable'
			],
			[
				'document_type.required' => 'Il campo tipo è obbligatorio.'
			]
		);
		
		$controllerClass = 'App\Http\Controllers\\' . $validatedData['document_type'];
		$controllerNewDocument = new $controllerClass();
		
		$prefix_code = $controllerNewDocument->prefix_code;
		$document_type = $controllerNewDocument->pattern;
		
		$numero = FunctionsHelper::getLastNumber($prefix_code, $this->model, $validatedData['year']);
		
		$document = $this->resolveModel($id);
		$document_indirizzo = $document->indirizzo;
		$prodottiData = $validatedData['prodotti'];
		$altroData = $validatedData['altro'];
		
		$new_document = null;
		$numero_val = $numero['numero'];
		try {
			DB::transaction(function () use (&$new_document, $document_type, $numero_val, $document, $document_indirizzo, $prodottiData, $altroData) {
			
				$new_document = $this->model::create([
					'type' => $document_type,
					'numero' => $numero_val,
					'data' => Carbon::now()->toDateString(),
					'entity_id' =>  $document->entity_id,
					'parent_id' => $document->id
				]);
				
				$indirizzo = [
					'nome' => $document_indirizzo->nome,
					'indirizzo' => $document_indirizzo->indirizzo,
					'comune' => $document_indirizzo->comune,
					'provincia' => $document_indirizzo->provincia,
					'cap' => $document_indirizzo->cap,
					'telefono' => $document_indirizzo->telefono,
					'note' => $document_indirizzo->note
				];
				
				DocumentIndirizzo::create(array_merge(['document_id' => $new_document->id], $indirizzo));
				
				foreach ($prodottiData as $prodotto) {
					DocumentProduct::create([
						'document_id' => $new_document->id,
						'quantita' => $prodotto['selectedQuantity'],
						'prezzo' => $prodotto['selectedPrezzo'],
						'tipo_sconto' => $prodotto['tipo_sconto'],
						'sconto' => $prodotto['sconto'],
						'product_id' => $prodotto['product_id'],
						'aliquota_iva_id' => $prodotto['aliquota_iva_id'],
						'ricorrenza' => $prodotto['ricorrenza'],
						'order' => $prodotto['order']
					]);
				}
				
				foreach ($altroData as $altro) {
					DocumentAltro::create([
						'document_id' => $new_document->id,
						'nome' => $altro['nome'],
						'quantita' => $altro['selectedQuantity'],
						'unita_misura' => $altro['unita_misura'],
						'prezzo' => $altro['prezzo'],
						'tipo_sconto' => $altro['tipo_sconto'],
						'sconto' => $altro['sconto'],
						'aliquota_iva_id' => $altro['aliquota_iva_id'],
						'ricorrenza' => $altro['ricorrenza'],
						'order' => $altro['order']
					]);
				}
			});
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
		
		$relation = $this->getRelation($document);

		return response()->json(['relation' => $relation, 'document_type' => $validatedData['document_type']]);
	}
	
	private function getEntityName(Document $document)
	{
		$entityKey = $this->entrata === true ? 'mittente' : 'destinatario';
		$entityName = $document->entity->nome ?? null;

		return [$entityKey => $entityName];
	}
	
	private function getIndirizzo(DocumentIndirizzo $document_indirizzo)
	{
		return [
			'id' => $document_indirizzo->id,
			'nome' => $document_indirizzo->nome,
			'indirizzo' => $document_indirizzo->indirizzo,
			'comune' => $document_indirizzo->comune,
			'provincia' => $document_indirizzo->provincia,
			'cap' => $document_indirizzo->cap,
			'telefono' => $document_indirizzo->telefono,
			'note' => $document_indirizzo->note
		];
	}
	
	private function getSpedizione(Document $document)
	{
		if($this->spedizione_active === true) {
			return [
				'prezzo' => $document->spedizione->prezzo ?? 0,
				'sconto' => $document->spedizione->sconto ?? 0,
				'iva' => [
					'aliquota_iva_id' => $document->spedizione->aliquotaIva->id ?? null,
					'aliquota' => $document->spedizione->aliquotaIva->aliquota ?? 0
				],
				'spedizione_id' => $document->spedizione->spedizione->id ?? 0
			];
		}
		return null;
	}
	
	private function getTrasporto(Document $document)
	{
		if($this->trasporto_active === true) {
			return [
				'id' => $document->trasporto->id ?? null,
				'colli' => $document->trasporto->colli ?? null,
				'peso' => $document->trasporto->peso ?? 0,
				'causale' => $document->trasporto->causale ?? null,
				'porto' => $document->trasporto->porto ?? null,
				'a_cura' => $document->trasporto->a_cura ?? null,
				'vettore' => $document->trasporto->vettore ?? null,
				'annotazioni' => $document->trasporto->annotazioni ?? null
			];
		}
		return null;	
	}
	
	private function getRate(Document $document) 
	{
		$data = [];
		
		foreach($document->rate as $rata) {
			$data[] = [
				'data' => $rata->data ? Carbon::createFromFormat('Y-m-d', $rata->data) : null,
				'percentuale' => $rata->percentuale,
				'importo' => $rata->importo
			];
		}
		
		return $data;
	}
	
	private function getIntestatari() 
	{
		$data = [];
		
		foreach($this->intestatari as $value) {
			$data[$value] = Entity::{$value}()->select('id', 'nome')->with('indirizzi')->get();
		}
		
		return $data;
	}
	
	private function getRelation(Document $document) 
	{
		$parentRelations = $document->parent ? collect([$document->parent]) : collect();
		$childRelations = $document->children ?? collect();

		$parents = $parentRelations->map(function ($relation) {
			return [
				'data' => $relation->data,
				'type' => $relation->type,
				'numero' => $relation->numero,
				'link' => $relation->getLinkIndex($relation->type) . '?' . 'filter=' . $relation->numero
			];
		})->toArray();

		$children = $childRelations->map(function ($relation) {
			return [
				'data' => $relation->data,
				'type' => $relation->type,
				'numero' => $relation->numero,
				'link' => $relation->getLinkIndex($relation->type) . '?' . 'filter=' . $relation->numero
			];
		})->toArray();

		return [
			'parents' => $parents,
			'children' => $children
		];
	}	
}