<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    * @return BelongsToMany
    */
    public function tariffs()
    {
        return $this->belongsToMany(Tariff::class);
    }
}
