<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerTariff extends Model
{
    protected $table = "customer_tariff";

    public function tariffs()
    {
        return $this->hasMany(Tariff::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
