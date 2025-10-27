<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkm';

    protected $fillable = [
        'name',
        'type',
        'photoUrl',
        'description',
        'latitude',
        'longitude',
        'address',
        'userId',
    ];
}
