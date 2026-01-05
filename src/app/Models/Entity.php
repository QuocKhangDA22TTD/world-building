<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    const CREATED_AT = null;
    
    protected $fillable = ['world_id', 'entity_type_id', 'name', 'description'];

    public function world()
    {
        return $this->belongsTo(World::class);
    }

    public function entityType()
    {
        return $this->belongsTo(EntityType::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'entity_tags');
    }

    public function relationshipsFrom()
    {
        return $this->hasMany(Relationship::class, 'from_entity_id');
    }

    public function relationshipsTo()
    {
        return $this->hasMany(Relationship::class, 'to_entity_id');
    }

    public function allRelationships()
    {
        return $this->relationshipsFrom->merge($this->relationshipsTo);
    }
}
