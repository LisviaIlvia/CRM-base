<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titolo',
        'descrizione',
        'entity_id',
        'assigned_to',
        'stato',
        'priorita',
        'categoria',
        'data_risoluzione'
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }
} 