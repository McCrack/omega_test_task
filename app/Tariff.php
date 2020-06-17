<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    * @return BelongsToMany
    */
    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function company()
	{
		return $this->belongsTo(Company::class);
	}
}
