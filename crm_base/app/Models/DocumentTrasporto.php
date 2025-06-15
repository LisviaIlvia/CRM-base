<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTrasporto extends Model
{
    protected $table = 'documents_trasporto';
    protected $fillable = ['colli', 'peso', 'causale', 'porto', 'a_cura', 'vettore', 'annotazioni', 'document_id'];
	
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}