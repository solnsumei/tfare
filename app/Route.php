<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['source_id', 'destination_id', 'fares', 'terminal_id'];
    protected $with = ['source', 'destination'];

    protected $casts = [
        'fares' => 'array',
    ];


    public function source() {
        return $this->belongsTo(City::class, 'source_id')->select(['id', 'name']);
    }

    public function destination() {
        return $this->belongsTo(City::class, 'destination_id')->select(['id', 'name']);;
    }

    public function terminal() {
        return $this->belongsTo(Terminal::class, 'terminal_id');
    }

    public function getFromAttribute() {
        return $this->source->name;
    }

    public function getToAttribute() {
        return $this->destination->name;
    }
}
