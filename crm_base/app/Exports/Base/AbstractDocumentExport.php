<?php

namespace App\Exports\Base;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\Document;
use App\Helpers\FunctionsHelper;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbstractDocumentExport implements FromCollection, WithHeadings, WithTitle, WithColumnWidths, WithStyles
{
    protected bool $entrata = false;
    protected string $type;
    protected string $title;
    private string $year;

    public function __construct($object, $year)
    {
        $this->year = $year;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function collection()
    {
        return Document::whereYear('data', $this->year)
            ->when($this->type, function ($query) {
                return $query->where('type', $this->type);
            })
            ->orderBy('data', 'asc')
            ->get()
            ->map(function ($element) {
                $imponibile = (float) number_format(FunctionsHelper::calculateImponibile($element), 2, ',', '');
                
                return [
                    'Numero' => $element->numero,
                    $this->entrata === true ? 'Mittente' : 'Destinatario' => $element->entity->nome,
                    'Data' => Carbon::createFromFormat('Y-m-d', $element->data)->format('d/m/Y'),
                    'Imponibile' => $imponibile,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Numero',
            $this->entrata === true ? 'Mittente' : 'Destinatario',
            'Data',
            'Imponibile'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 15,
            'D' => 15
        ];
    }

	public function styles($sheet)
	{
		$sheet->getStyle('A1:D' . $sheet->getHighestRow())
			->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

		$sheet->getStyle('A1:D1')
			->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

		$sheet->getStyle('A1:D1')->getFont()->setBold(true);

		$sheet->getStyle('A1:D1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('BBDEFB');

		$sheet->getStyle('C2:C' . $sheet->getHighestRow())
			->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

		$sheet->getStyle('D2:D' . $sheet->getHighestRow())
			->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

		$sheet->getStyle('C2:C' . $sheet->getHighestRow())
			->getNumberFormat()->setFormatCode('DD/MM/YYYY');

		$sheet->getStyle('D2:D' . $sheet->getHighestRow())
			->getNumberFormat()->setFormatCode('#,##0.00');
	}
}
