<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'mobile',
        'address_1',
        'address_2',
        'city',
        'county',
        'postcode',
        'country',
        'stripe_customer_id'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getStripeFormattedAddressAttribute()
    {
        return [
            'line1' => $this->address_1,
            'line2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->county,
            'postal_code' => $this->postcode,
            'country' => $this->country,
        ];
    }

    /**
     * Get the customer's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to only include customers from a specific country.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // mutator (to format data before saving)
    public function setMobileAttribute($value)
    {
        // Remove non-numeric characters
        $this->attributes['mobile'] = preg_replace('/[^0-9]/', '', $value);
    }

    // accessor (to format data when retrieving)
    public function getFormattedMobileAttribute()
    {
        if ($this->mobile) {
            // Example: Format as (XXX) XXX-XXXX
            return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $this->phone);
        }
        return null;
    }
}
