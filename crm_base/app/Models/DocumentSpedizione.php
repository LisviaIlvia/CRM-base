<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentSpedizione extends Model
{
    protected $table = 'documents_spedizioni';
    protected $fillable = ['prezzo', 'sconto', 'aliquota_iva_id', 'spedizione_id', 'document_id'];

    public function spedizione()
    {
        return $this->belongsTo(Spedizione::class, 'spedizione_id');
    }
	
	public function aliquotaIva()
    {
        return $this->belongsTo(AliquotaIva::class, 'aliquota_iva_id');
    }
	
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}