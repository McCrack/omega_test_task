<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
	protected $table = "companies";

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'meta',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
