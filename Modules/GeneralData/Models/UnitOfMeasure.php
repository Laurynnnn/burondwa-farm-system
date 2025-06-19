<?php

namespace Modules\GeneralData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitOfMeasure extends Model
{
    use SoftDeletes;

    protected $table = 'units_of_measure';

    protected $fillable = [
        'name',
        'abbreviation',
        'description',
    ];
} 