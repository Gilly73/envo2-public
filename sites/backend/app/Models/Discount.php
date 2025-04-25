<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Discount extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
       'discount',
       'code',
       'country_id',
       'active'
    ];


}