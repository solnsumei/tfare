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
    protected $appends = ['company', 'nearestPark'];

    protected $hidden = ['park', 'attachedCompany'];

    public function attachedCompany() {
        return $this->belongsTo(Company::class, 'company_id')->select(['id', 'slug', 'name', 'logoUrl']);
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id')->select(['id', 'name', 'country']);
    }

    public function park() {
        return $this->belongsTo(Park::class, 'park_id')->select(['id', 'name', 'slug', 'city_id']);
    }

    public function routes() {
        return $this->hasMany(Route::class);
    }

    public function getNearestParkAttribute() {
        return $this->park;
    }

    public function getCompanyAttribute() {
        return $this->attachedCompany;
    }
}
