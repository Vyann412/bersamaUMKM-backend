<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkms';

    protected $fillable = [
        'name',
        'type',
        'photoUrl',
        'description',
        'latitude',
        'longitude',
        'address',
        'rating',
        'userId',
    ];
}
