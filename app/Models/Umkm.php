<?php

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkm';

    protected $fillable = [
        'name',
        'photoUrl',
        'description',
        'latitude',
        'longitude',
        'userId',
    ];
}
