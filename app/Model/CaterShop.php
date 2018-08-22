<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CaterShop extends Model
{
    protected $table = 'cater_shop';

    protected $fillable = [
        'name', 'begin_time', 'end_time', 'status', 'logo'
    ];
}
