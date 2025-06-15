<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class FatturaAcquistoExport extends AbstractDocumentExport
{
	protected bool $entrata = true;
	protected string $type = 'fatture-acquisto';
    protected string $title = 'Fattura Acquisto';
}