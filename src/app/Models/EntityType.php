<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityType extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['world_id', 'name'];

    public function world()
    {
        return $this->belongsTo(World::class);
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }
}
