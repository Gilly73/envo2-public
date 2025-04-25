<?php

namespace App\Models\Couch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Seater extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
       'description',
       'price',
       'couch_type_id',
       'seat_count',
    ];


}
