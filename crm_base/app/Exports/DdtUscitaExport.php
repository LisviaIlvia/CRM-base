<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class DdtUscitaExport extends AbstractDocumentExport
{
	protected string $type = 'ddt-uscita';
    protected string $title = 'DDT Uscita';
}