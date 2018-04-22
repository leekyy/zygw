<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:19
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HuxingStyle extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 't_huxing_style';
    public $timestamps = true;
    protected $dates = ['deleted_at'];  //软删除
}
