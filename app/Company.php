<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'logoUrl', 'slug'];

    public function terminals() {
        return $this->hasMany(Terminal::class)->select(['id', 'company_id', 'name', 'phone', 'address', 'city_id', 'park_id']);
    }
}
