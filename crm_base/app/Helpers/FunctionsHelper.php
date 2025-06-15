<?php

namespace App\Helpers;

class FunctionsHelper
{
	public static function getLastCode(string $prefix, string $model) 
	{
		$lastCode = $model::where('codice', 'like', $prefix . '%')
			->orderByRaw('CAST(SUBSTRING(codice, LENGTH(?) + 1) AS UNSIGNED) desc', [$prefix])
			->first();

		$newCode = $lastCode 
			? str_pad((int)substr($lastCode->codice, strlen($prefix)) + 1, 9, '0', STR_PAD_LEFT)
			: str_pad('1', 9, '0', STR_PAD_LEFT);

		return $prefix . $newCode;
	}
	
	public static function getLastNumber(string $prefix, string $model, ?string $year = null)
	{
		$year = $year ?? request()->query('year');

		$lastNumber = $model::whereYear('data', $year)
			->where('numero', 'LIKE', "{$prefix}%")
			->orderByRaw('CAST(SUBSTRING(numero, LENGTH(?) + 1, LOCATE("/", numero) - LENGTH(?) - 1) AS UNSIGNED) DESC', [$prefix, $prefix])
			->first();

		if ($lastNumber && strpos($lastNumber->numero, '/') !== false) {
			$parts = explode('/', $lastNumber->numero);
			$incrementedNumber = intval(substr($parts[0], strlen($prefix))) + 1;
		} else {
			$incrementedNumber = 1;
		}

		$formattedNumber = str_pad($incrementedNumber, 5, '0', STR_PAD_LEFT);

		$number = "{$prefix}{$formattedNumber}/{$year}";

		return [
			'numero' => $number,
			'previous_doc_date' => $lastNumber->data ?? ''
		];
	}
	
	public static function getRangeData(string $model, string $data)
	{
		$previousDoc = $model::where('data', '<', $data)
			->orderBy('data', 'desc')
			->first();

		$nextDoc = $model::where('data', '>', $data)
			->orderBy('data', 'asc')
			->first();

		return [
			'previous_doc_date' => $previousDoc->data ?? '',
			'next_doc_date' => $nextDoc->data ?? ''
		];
	}

	public static function calculateImponibile(Object $element) {
		$imponibile = 0;
		$elementi = self::createElements($element);
		foreach($elementi as $elemento) {
			if($elemento['tipo'] != 'descrizione') $imponibile += $elemento['importo'];
		}
		if($element->spedizione) $imponibile += $element->spedizione->prezzo - ($element->spedizione->prezzo * $element->spedizione->sconto);
		
		return $imponibile;
	}
	
    public static function countProductQuantities(Object $element) {
        $elementi = self::createElements($element);
        $totalQuantity = 0;
        foreach($elementi as $elemento) {
            if(isset($elemento['quantita'])) {
                $totalQuantity += $elemento['quantita'];
            }
        }
        return $totalQuantity;
    }
	
	public static function createElements(Object $element) {
		
		$elementi = [];
		
		foreach($element->products as $product) {
			$subtotale_product = $product->quantita * $product->prezzo;
			$elementi[] = [
				'tipo' => $product->product->type,
				'id' => $product->product_id,
				'nome' => $product->product->nome,
				'codice' => $product->product->codice,
				'quantita' => $product->quantita,
				'unita_misura' => $product->product->unita_misura,
				'prezzo' => $product->prezzo,
				'tipo_sconto' => $product->tipo_sconto,
				'sconto' => $product->sconto,
				'importo' => $product->tipo_sconto == '%' ? $subtotale_product - (($subtotale_product * $product->sconto)/100) : $subtotale_product - $product->sconto,
				'iva' => [
					'aliquota_iva_id' => $product->aliquotaIva->id,
					'aliquota' => $product->aliquotaIva->aliquota,
					'descrizione' => $product->aliquotaIva->descrizione
				],
				'ricorrenza' => $product->ricorrenza,
				'element_id' => $product->id,
				'order' => $product->order
			];
		}
		
		foreach($element->altro as $altro) {
			$subtotale_altro = $altro->quantita * $altro->prezzo;
			$elementi[] = [
				'tipo' => 'altro',
				'nome' => $altro->nome,
				'quantita' => $altro->quantita,
				'unita_misura' => $altro->unita_misura,
				'prezzo' => $altro->prezzo,
				'tipo_sconto' => $altro->tipo_sconto,
				'sconto' => $altro->sconto,
				'importo' => $altro->tipo_sconto == '%' ? $subtotale_altro - (($subtotale_altro * $altro->sconto)/100) : $subtotale_altro - $altro->sconto,
				'iva' => [
					'aliquota_iva_id' => $altro->aliquotaIva->id,
					'aliquota' => $altro->aliquotaIva->aliquota
				],
				'ricorrenza' => $altro->ricorrenza,
				'element_id' => $altro->id,
				'order' => $altro->order
			];
		}
		
		foreach($element->descrizioni as $descrizione) {
			$elementi[] = [
				'tipo' => 'descrizione',
				'descrizione' => $descrizione->descrizione,
				'element_id' => $descrizione->id,
				'order' => $descrizione->order
			];
		}
		
		usort($elementi, function ($a, $b) {
			return $a['order'] <=> $b['order'];
		});
		
		return $elementi;
		
	}
	
}