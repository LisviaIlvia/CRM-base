<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class FatturaProformaExport extends AbstractDocumentExport
{
	protected string $type = 'fatture-proforma';
    protected string $title = 'Fattura Proforma';
}