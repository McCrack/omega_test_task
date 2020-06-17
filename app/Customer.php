<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'is_active'
    ];

    

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function tariffs()
    {
        return $this->belongsToMany(Tariff::class);
    }
}
