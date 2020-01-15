<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Park extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'city_id', 'slug'];

    public function city() {
        return $this->belongsTo(City::class, 'city_id')->select(['id', 'name']);
    }

    public function getCityAttribute() {
        return $this->town->name;
    }
}
