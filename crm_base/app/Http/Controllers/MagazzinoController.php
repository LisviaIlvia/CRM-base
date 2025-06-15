<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MagazzinoExport;
use App\Http\Controllers\Base\AbstractCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class MagazzinoController extends AbstractCrudController
{
	protected string $pattern = 'magazzino';
	protected bool  $activeYear = true;
	protected bool $recordsYear = true;
	protected bool $export = true;
	protected bool $create = false;
	
	protected array $indexSetup = [
		'plural' => 'Magazzino',
		'single' => 'Magazzino',
		'type' => 'm',
		'icon' => 'fa-solid fa-warehouse',
		'headers' => [
			['title' => 'Codice', 'key' => 'codice', 'sortable' => true],
			['title' => 'Nome', 'key' => 'nome', 'sortable' => true],
			['title' => 'Iniziale', 'key' => 'giacenza_iniziale', 'sortable' => false, 'align' => 'center'],
			['title' => 'Entrata', 'key' => 'entrata', 'sortable' => false, 'align' => 'center'],
			['title' => 'Uscita', 'key' => 'uscita', 'sortable' => false, 'align' => 'center'],
			['title' => 'Ordine', 'key' => 'ordine', 'sortable' => false, 'align' => 'center'],
			['title' => 'Venduto', 'key' => 'venduto', 'sortable' => false, 'align' => 'center'],
			['title' => 'C. Dep.', 'key' => 'conto_deposito', 'sortable' => false, 'align' => 'center'],
			['title' => 'Disponibile', 'key' => 'disponibile', 'sortable' => false, 'align' => 'center'],
		]
	];
	
	protected function getDataIndex(Collection $collection, array $actionPermissions)
	{
		return $collection->map(function ($movimentiAnno, $anno) {
			return [
				'year' => $anno,
				'records' => collect($movimentiAnno)->map(function ($element) {
					return [
						'id' => $element['id'],
						'codice' => $element['codice'],
						'nome' => $element['nome'],
						'giacenza_iniziale' => $element['giacenza_iniziale'],
						'entrata' => $element['entrata'],
						'uscita' => $element['uscita'],
						'venduto' => $element['venduto'],
						'ordine' => $element['ordine'],
						'conto_deposito' =>$element['conto_deposito'],
						'disponibile' => $element['disponibile']
					];
				})->values()
			];
		})->values();
	}
	
	protected function getCollectionIndex()
	{
		$merci = Product::merci()
		->with([
			'documentProduct.document'
		])->get();
		
		foreach ($merci as $merce) {
			$merce->setRelation('ddtEntrataProdotti', $merce->documentProductFilter('ddt-entrata')->with('document')->get());
			$merce->setRelation('ddtUscitaProdotti', $merce->documentProductFilter('ddt-uscita')->with('document')->get());
			$merce->setRelation('ordiniVenditaProduct', $merce->documentProductFilter('ordini-vendita')->with('document')->get());
			$merce->setRelation('fatturaVenditaProduct', $merce->documentProductFilter('fatture-vendita')->with('document')->get());
		}
		
		$annoIniziale = config('app.anno_iniziale');
		$annoAttuale = Carbon::now()->year;

		$movimentiPerAnno = [];

		for($anno = $annoIniziale; $anno <= $annoAttuale; $anno++) {
			foreach ($merci as $merce) {
				$movimentiPerAnno[$anno][$merce->id] = [
					'id' => $merce->id,
					'codice' => $merce->codice,
					'nome' => $merce->nome,
					'giacenza_iniziale' => 0,
					'entrata' => 0,
					'uscita' => 0,
					'ordine' => 0,
					'venduto' => 0
				];
			}
		}

		foreach($merci as $merce) {
			$giacenza_iniziale = $merce->giacenza_iniziale;
			$entrate = $merce->ddtEntrataProduct;
			$uscite = $merce->ddtUscitaProduct;
			$ordini_vendita = $merce->ordiniVenditaProduct;
			$fatture_vendita = $merce->fatturaVenditaProduct;
			
			$movimentiPerAnno[$annoIniziale][$merce->id]['giacenza_iniziale'] += $giacenza_iniziale;

			foreach($entrate as $entrata) {
				if($entrata->document) {
					$anno = date('Y', strtotime($entrata->document->data));
					$movimentiPerAnno[$anno][$merce->id]['entrata'] += $entrata->quantita;
				}
			}

			foreach($uscite as $uscita) {
				if($uscita->document) {
					$anno = date('Y', strtotime($uscita->document->data));
					$movimentiPerAnno[$anno][$merce->id]['uscita'] += $uscita->quantita;
				}
			}
			
			foreach($ordini_vendita as $ordine) {
				if($ordine->document) {
					$anno = date('Y', strtotime($ordine->document->data));
					$movimentiPerAnno[$anno][$merce->id]['ordine'] += $ordine->quantita;
				}
			}
			
			foreach($fatture_vendita as $fattura) {
				if($fattura->document) {
					$anno = date('Y', strtotime($fattura->document->data));
					$movimentiPerAnno[$anno][$merce->id]['venduto'] += $fattura->quantita;
				}
			}
		}

		foreach($merci as $merce) {
			for ($anno = $annoIniziale; $anno <= $annoAttuale; $anno++) {
				$movimenti = &$movimentiPerAnno[$anno][$merce->id];
				$movimenti['disponibile'] = $movimenti['giacenza_iniziale'] + $movimenti['entrata'] - $movimenti['uscita'] - $movimenti['ordine'];
				$movimenti['conto_deposito'] = (($movimenti['ordine'] + $movimenti['uscita']) == 0) ? 0 : ($movimenti['uscita'] - $movimenti['venduto']);
				
				if ($anno < $annoAttuale) {
					$annoSuccessivo = $anno + 1;
					if ($annoSuccessivo <= $annoAttuale) {
						$movimentiPerAnno[$annoSuccessivo][$merce->id]['giacenza_iniziale'] = $movimenti['disponibile'];
					}
				}
			}
		}

		return new Collection($movimentiPerAnno);
	}
	
	protected function setComponents()
	{
		return [
			'index' => 'magazzino/MagazzinoIndex'
		];
	}
	
	protected function setJsonData(string $type, Model|Collection $object)
	{
		return [
			'id' => $object->id,
			'codice' => $object->codice,
			'nome' => $object->nome,
			'giacenza_iniziale' => $object->giacenza_iniziale,
			'entrata' => $object->entrata,
			'uscita' => $object->uscita,
			'venduto' => $object->venduto,
			'conto_deposito' => $object->conto_deposito,
			'disponibile' => $object->disponibile
		];
	}
	
	public function export(Request $request, $id) 
    {
		$year = $request->query('year', date('Y'));
		
		$exportClass = MagazzinoExport::class;
		$exportFile = 'magazzino_' . $year;
		
        return Excel::download(new $exportClass($year), $exportFile . '.xlsx');
    }
}