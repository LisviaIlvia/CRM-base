<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class OrdineVenditaExport extends AbstractDocumentExport
{
	protected string $type = 'ordini-vendita';
    protected string $title = 'Ordine Vendita';
}