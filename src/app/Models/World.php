<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class World extends Model
{
    const UPDATED_AT = null;
    
    protected $fillable = ['owner_id', 'name', 'description'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function entityTypes()
    {
        return $this->hasMany(EntityType::class);
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
