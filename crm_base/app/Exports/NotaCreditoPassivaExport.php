<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class NotaCreditoPassivaExport extends AbstractDocumentExport
{
	protected bool $entrata = true;
	protected string $type = 'note-credito-passive';
    protected string $title = 'Nota Credito';
}