<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaterCategory extends Model
{
    use SoftDeletes;

    protected $table = 'cater_category';

    protected $fillable = [
        'cate_name','sort'
    ];

    protected $dates = ['delete_at'];
}
