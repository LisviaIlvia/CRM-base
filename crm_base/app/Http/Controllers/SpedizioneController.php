<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Spedizione;
use App\Models\AliquotaIva;
use Illuminate\Validation\Rule;

class SpedizioneController extends AbstractCrudController
{
	protected string $pattern = 'spedizioni';
	protected string $model = Spedizione::class;
	protected array $verifyDestroy = ['documentSpedizione'];

	protected array $indexSetup = [
		'plural' => 'Spedizioni',
		'single' => 'Spedizione',
		'type' => 'f',
		'icon' => 'fa-solid fa-truck-fast',
		'headers' => [
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Predefinita', 'key' => 'predefinita', 'sortable' => false],
			['title' => 'Azioni', 'key' => 'actions', 'sortable' => false, 'align' => 'end']
		]
	];

	protected function beforeStore(&$validatedData)
	{
		if (isset($validatedData['prezzo_iva'])) {
			$validatedData['prezzo'] = $validatedData['prezzo_iva']['prezzo'] ;
			$validatedData['aliquota_iva_id'] = $validatedData['prezzo_iva']['aliquota_iva_id'];
		}
	}

	protected function beforeUpdate(&$validatedData)
	{
		if ($validatedData['predefinita']) {
            $this->model::query()->update(['predefinita' => 0]);
        }

		if (isset($validatedData['prezzo_iva'])) {
			$validatedData['prezzo'] = $validatedData['prezzo_iva']['prezzo'] ;
			$validatedData['aliquota_iva_id'] = $validatedData['prezzo_iva']['aliquota_iva_id'];
		}
	}

	protected function setComponents()
	{
		return [
			'index' => 'spedizioni/SpedizioniIndex',
			'create' => 'Crud/CrudCreate',
			'show' => 'Crud/CrudShow',
			'edit' => 'Crud/CrudEdit',
			'content' => 'spedizioni/SpedizioniContent'
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
			'show', 'edit' => [
				'descrizione' => $object->descrizione,
				'aliquota_iva_id' => $object->aliquota_iva_id,
				'prezzo_iva' => [
					'prezzo' => $object->prezzo,
					'aliquota_iva_id' => $object->aliquota_iva_id
				]
			],
			default => []
		};

		return array_merge(['main' => $main], [$type => $data]);
	}

	protected function setOtherData(string $type, Model $object)
	{
		$aliquote_iva = AliquotaIva::all();

		return ['aliquote_iva' => $aliquote_iva];
	}

	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'prezzo_iva.prezzo' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
				'prezzo_iva.aliquota_iva_id' => 'required',
				'descrizione' => 'nullable',
				'predefinita' => 'required'
			],
			'store' => [
				'nome' => [
					'required',
					Rule::unique('spedizioni', 'nome')->whereNull('deleted_at')
				]
			],
			'update' => [
				'nome' => [
					'required',
					Rule::unique('spedizioni', 'nome')
						->ignore($object->id)
						->whereNull('deleted_at'),
				]
			],
			'messages' => [
				'nome.required' => 'Il campo nome è obbligatorio.',
				'nome.unique' => "Il nome della spedizione deve essere unico.",
				'prezzo_iva.prezzo.required' => 'Il campo prezzo è obbligatorio.',
				'prezzo_iva.prezzo.numeric' => 'Il prezzo deve essere un numero.',
				'prezzo_iva.prezzo.min' => 'Il prezzo non può essere negativo.',
				'prezzo_iva.prezzo.regex' => 'Il prezzo deve essere un valore decimale con massimo due cifre decimali.',
				'prezzo_iva.aliquota_iva_id.required' => 'Il campo aliquota iva è obbligatorio.',
				'predefinita.required' => 'Il campo predefinita è obbligatorio.'
			]
		];
	}
}
