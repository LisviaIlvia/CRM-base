<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class FatturaVenditaExport extends AbstractDocumentExport
{
	protected string $type = 'fatture-vendita';
    protected string $title = 'Fattura Vendita';
}