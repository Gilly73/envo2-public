<?php

namespace App\Models\Couch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Fabric extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
       'material',
       'price',
       'couch_type_id',
    ];


}
