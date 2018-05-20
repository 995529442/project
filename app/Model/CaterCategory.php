<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CaterCategory extends Model
{
    protected $table = 'cater_category';

    protected $fillable = [
        'cate_name','sort'
    ];
}
