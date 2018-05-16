<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaterGoods extends Model
{
    use SoftDeletes;

    protected $table = 'cater_goods';

    protected $fillable = [
        'cate_id','good_name','is_hot','is_new','is_recommend','thumb_img','original_price','now_price','introduce'
    ];

    protected $dates = ['delete_at'];
}
