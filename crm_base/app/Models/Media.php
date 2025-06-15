<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'name',
        'extension',
        'mime_type',
		'url',
        'relationable_id',
        'relationable_type'
    ];

    public function relationable()
    {
        return $this->morphTo();
    }
}
