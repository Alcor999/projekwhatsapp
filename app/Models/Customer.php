<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'phone_number',
        'debtor_name',
        'contract_number',
        'vehicle_unit',
    ];
}
