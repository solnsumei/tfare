<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'phone', 'address', 'city_id', 'park_id', 'slug'];
    protected $appends = ['city', 'park'];
    protected $hidden = ['attachedCity', 'nearestPark'];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id')->select(['id', 'slug', 'name', 'logoUrl']);
    }

    public function attachedCity() {
        return $this->belongsTo(City::class, 'city_id')->select(['id', 'name', 'country']);
    }

    public function nearestPark() {
        return $this->belongsTo(Park::class, 'park_id')->select(['id', 'city_id', 'name', 'slug']);
    }

    public function routes() {
        return $this->hasMany(Route::class);
    }

    public function getCityAttribute() {
        return $this->attachedCity;
    }

    public function getParkAttribute() {
        return $this->nearestPark;
    }
}
