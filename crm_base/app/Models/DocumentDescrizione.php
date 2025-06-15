<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentDescrizione extends Model
{
    protected $table = 'documents_descrizioni';
    protected $fillable = ['descrizione', 'order', 'document_id'];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}