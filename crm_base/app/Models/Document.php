<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Document extends Model
{
    use SoftDeletes;

    protected $table = 'documents';
    protected $fillable = ['type', 'stato', 'numero', 'data', 'note', 'metodo_pagamento_id', 'conto_bancario_id', 'entity_id', 'parent_id'];

	public function metodoPagamento()
    {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }
	
	public function contoBancario()
    {
        return $this->belongsTo(ContoBancario::class, 'conto_bancario_id');
    }
	
	public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

	public function products()
    {
         return $this->hasMany(DocumentProduct::class, 'document_id');
    }

    public function altro()
    {
		return $this->hasMany(DocumentAltro::class, 'document_id');
    }

    public function descrizioni()
    {
		return $this->hasMany(DocumentDescrizione::class, 'document_id');
    }

    public function spedizione()
    {
		return $this->hasOne(DocumentSpedizione::class, 'document_id');
    }
	
	public function trasporto()
    {
		return $this->hasOne(DocumentTrasporto::class, 'document_id');
    }

    public function indirizzo()
    {
		return $this->hasOne(DocumentIndirizzo::class, 'document_id');
    }
	
	public function rate()
    {
         return $this->hasMany(DocumentRata::class, 'document_id');
    }
	
	public function media()
    {
        return $this->morphMany(Media::class, 'relationable');
    }
	
	public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }
	
	public function getLinkIndex(string $type)
	{
		return route($type . '.index');
	}
		
	public static function getDocuments(string $type)
	{
		return self::where('type', $type);
	}
}