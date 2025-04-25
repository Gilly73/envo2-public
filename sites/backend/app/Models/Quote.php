<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'active',
        'customer_id',
        // Request data columns
        'couchtype',
        'styletype',
        'fabrictype',
        'legtype',
        'seatertype',
        'discount_code',
        'country',
        // Response data columns
        'description',
        'couch_cost',
        'discount',
        'tax',
        'total_cost',
    ];

    public function scopeOfActive($query, $active)
    {
        return $query->where('active', $active);
    }
}
