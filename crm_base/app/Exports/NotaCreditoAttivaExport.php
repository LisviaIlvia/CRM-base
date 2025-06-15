<?php

namespace App\Exports;

use App\Exports\Base\AbstractDocumentExport;

class NotaCreditoAttivaExport extends AbstractDocumentExport
{
	protected string $type = 'note-credito-attive';
    protected string $title = 'Nota Credito';
}