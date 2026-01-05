<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['world_id', 'name'];

    public function world()
    {
        return $this->belongsTo(World::class);
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class, 'entity_tags');
    }
}
