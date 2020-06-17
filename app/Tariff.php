<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $table = "tariffs";

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'compani_id', 'name', 'description', 'price',
    ];

    
    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
