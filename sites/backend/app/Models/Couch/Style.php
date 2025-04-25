<?php

namespace App\Models\Couch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Style extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
       'name',
       'base_price',
       'couch_type_id',
    ];


}
