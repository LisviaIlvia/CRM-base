<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class OrdineAcquistoExport extends AbstractDocumentExport
{
	protected bool $entrata = true;
	protected string $type = 'ordini-acquisto';
    protected string $title = 'Ordine Acquisto';
}