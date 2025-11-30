<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nette\PhpGenerator\Attribute as PhpGeneratorAttribute;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'registration_number',
        'driver_name',
        'latitude',
        'longitude',
        'status',
        'capacity',
    ];

}
