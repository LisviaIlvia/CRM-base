<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AbstractCrudController;
use App\Models\Azienda;
use App\Http\Controllers\AziendaIndirizzoController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;

class AziendaController extends AbstractCrudController 
{
	protected string $pattern = 'azienda';
	protected string $model = Azienda::class;
	
	protected array $indexSetup = [
		'plural' => 'Azienda',
		'single' => 'Azienda',
		'type' => 'a',
		'icon' => 'fa-solid fa-building'
	];
	
	public function index()
	{
		$azienda = Azienda::find(1);
		
		$props = [
			'title' => $this->indexSetup['plural'],
			'single' => $this->indexSetup['single'],
			'type' => $this->indexSetup['type'],
			'icon' => $this->indexSetup['icon'],
			'data' => array_merge(
				$this->getJsonData('index', $azienda),
				['actions' => $this->getAction($azienda, ['edit' => 'edit', 'update' => 'edit'])]
			),
			'dialogSetup' => ['create' => null, 'edit' => null, 'show' => null]
		];

		return Inertia::render('azienda/AziendaIndex', $props);
	}
	
	protected function getJsonData(string $type, Model|Collection $object = null, bool $create = false)
	{
		$object = $object ?? new $this->model;
		
		$data = [
			'id' => $object->id,
			'ragione_sociale' => $object->ragione_sociale,
			'partita_iva' => $object->partita_iva,
			'codice_fiscale' => $object->codice_fiscale,
			'pec' => $object->pec,
			'logo' => $object->logo,
			'indirizzi' => $this->getIndirizzi($object, )
		];
		
		if ($object->exists && $type == 'update') {
			$data['actions'] = $this->getAction($object, ['edit' => 'edit', 'update' => 'edit']);
		}

        return $data;
	}
	
	private function getIndirizzi(
		Model $object, 
		array $actionPermissions = [
			'show' => 'show', 
			'edit' => 'edit', 
			'update' => 'edit', 
			'destroy' => 'delete'
		],
		bool $create = true
	)
	{
		return (new AziendaIndirizzoController)->getPropsIndex($object->indirizzi, $actionPermissions, $create);
	}
	
	protected function setValidation(Model $object)
	{
		return [
			'rules' => [
				'ragione_sociale' => 'required',
				'partita_iva' => 'required',
				'codice_fiscale' => 'required',
				'pec' => 'required|email',
				'logo' => 'nullable'
			],
			'messages' => [
				'ragione_sociale.required' => 'La ragione sociale è obbligatoria.',
				'partita_iva.required' => 'La partita IVA è obbligatoria.',
				'codice_fiscale.required' => 'Il codice fiscale è obbligatorio.',
				'pec.required' => "La PEC è obbligatoria.",
				'pec.email' => 'Inserire un indirizzo PEC valido.'
			]
		];
	}
}