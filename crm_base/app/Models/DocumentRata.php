<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRata extends Model
{
    protected $table = 'documents_rate';
    protected $fillable = ['data', 'percentuale', 'importo', 'document_id'];
	
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}