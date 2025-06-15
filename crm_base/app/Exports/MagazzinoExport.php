<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use App\Models\Product;

class MagazzinoExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStyles, WithColumnWidths
{
    protected string $title = 'Merci';
    protected int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function collection()
    {
		$merci = Product::merci()
		->with([
			'documentProduct.document'
		])
		->get();
		
		foreach ($merci as $merce) {
			$merce->setRelation('ddtEntrataProdotti', $merce->documentProductFilter('ddt-entrata')->with('document')->get());
			$merce->setRelation('ddtUscitaProdotti', $merce->documentProductFilter('ddt-uscita')->with('document')->get());
			$merce->setRelation('ordiniVenditaProduct', $merce->documentProductFilter('ordini-vendita')->with('document')->get());
			$merce->setRelation('fatturaVenditaProduct', $merce->documentProductFilter('fatture-vendita')->with('document')->get());
		}

		$annoIniziale = config('app.anno_iniziale');
		$annoAttuale = Carbon::now()->year;
		$annoRichiesto = $this->year;
		
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
		
        $movimentiPerAnno = collect($movimentiPerAnno);

        return collect(array_values($movimentiPerAnno[$annoRichiesto]));
    }

    public function map($merce): array
    {
        return [
            $merce['id'],
			$merce['codice'],
            $merce['nome'],
            $merce['giacenza_iniziale'],
            $merce['entrata'],
            $merce['uscita'],
			$merce['ordine'],
            $merce['venduto'],
			$merce['conto_deposito'],
            isset($merce['disponibile']) ? (string) $merce['disponibile'] : '0'
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
			'Codice',
            'Nome',
            'Giacenza Iniziale',
            'Entrata',
            'Uscita',
			'Ordine',
            'Venduto',
            'Conto Deposito',
            'Disponibile'
        ];
    }
	/**
		 * Configura i bordi e gli stili delle celle.
		 */
	public function styles(Worksheet $sheet)
	{
		// Applica bordi e stili per l'intestazione
		$sheet->getStyle('A1:J1')->applyFromArray([
			'font' => [
				'bold' => true,
				'color' => ['rgb' => 'FFFFFF'],
			],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => '4CAF50'], // Colore di sfondo
			],
			'alignment' => [
				'horizontal' => 'center',
				'vertical' => 'center',
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		]);

		// Applica bordi per tutto il dataset
		$totalRows = $sheet->getHighestRow();
		$sheet->getStyle("A1:J$totalRows")->applyFromArray([
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		]);

		// Formattazione condizionale per la colonna J (Disponibile)
		$conditional = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
		$conditional->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
		$conditional->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
		$conditional->addCondition('0'); // Valore negativo
		$conditional->getStyle()->getFont()->getColor()->setRGB('FF0000'); // Testo rosso

		// Applica la formattazione condizionale alla colonna J
		$conditionalStyles = $sheet->getStyle("J2:J$totalRows")->getConditionalStyles();
		$conditionalStyles[] = $conditional;
		$sheet->getStyle("J2:J$totalRows")->setConditionalStyles($conditionalStyles);
	}

    /**
     * Configura la larghezza delle colonne.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // Colonna ID
            'B' => 15,  // Colonna Codice
            'C' => 60,  // Colonna Nome
            'D' => 20,  // Colonna Giacenza Iniziale
            'E' => 15,  // Colonna Entrata
            'F' => 15,  // Colonna Uscita
            'G' => 15,  // Colonna Ordine
            'H' => 15,  // Colonna Venduto
            'I' => 20,  // Colonna Conto Deposito
			'J' => 15,  // Colonna Disponibile
        ];
    }
}
