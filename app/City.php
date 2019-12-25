<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'country'];

    public function parks() {
        return $this->hasMany(Park::class)->select(['id', 'city_id', 'name', 'slug']);
    }
}
