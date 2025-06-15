<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentIndirizzo extends Model
{
    protected $table = 'documents_indirizzi';
	protected $fillable = ['nome', 'indirizzo', 'comune', 'provincia', 'cap', 'note', 'telefono', 'document_id'];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}