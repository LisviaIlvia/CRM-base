<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $table = 'activities';
    protected $fillable = ['type', 'data', 'descrizione', 'entity_id'];
	
	public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}