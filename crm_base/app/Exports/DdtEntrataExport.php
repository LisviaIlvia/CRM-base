<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class DdtEntrataExport extends AbstractDocumentExport
{
	protected bool $entrata = true;
	protected string $type = 'ddt-entrata';
    protected string $title = 'DDT Entrata';
}