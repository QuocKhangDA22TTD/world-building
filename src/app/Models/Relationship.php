<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['world_id', 'from_entity_id', 'to_entity_id', 'relation_type', 'description'];

    public function world()
    {
        return $this->belongsTo(World::class);
    }

    public function fromEntity()
    {
        return $this->belongsTo(Entity::class, 'from_entity_id');
    }

    public function toEntity()
    {
        return $this->belongsTo(Entity::class, 'to_entity_id');
    }
}
