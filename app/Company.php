<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return HasMany
     */
    public function tariffs()
    {
        return $this->hasMany(Tariff::class);
    }

    /**
     * @return BelongsToMany
     */
    public function customers()
    {
        //return $this->belongsToMany(Customer::class);

        $tariffs = $this->hasMany(Tariff::class, 'company_id');

        return $tariffs
            ->getResults()
            ->belongsToMany(Customer::class, 'customer_tariff', 'tariff_id', 'customer_id');
    }
}
