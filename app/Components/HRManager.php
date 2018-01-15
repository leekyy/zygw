<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\HR;
use App\Models\HRD;
use App\Models\HRR;
use App\Models\HX;
use Illuminate\Support\Facades\DB;


class HRManager
{

    /*
     * 获取房源信息
     *
     * By yinyue
     *
     * 2017-11-21
     *
     *
     */
    public static function  getHRs(){
        $hrs = HR::where('state','=', '0')->get();
//        $hrs = DB::table('t_house_type')->join('t_housing_resources','t_house_type.id','=','t_housing_resources.type_id')
//            ->get();
        return $hrs;
    }

    /*
     * 根据id获取房源信息
     *
     * By yinyue
     *
     * 2017-11-21
     *
     *
     */
    public static function  getHRById($data){
        $id = $data['id'];
        $hr = HR::find($id);
        return $hr;
    }

    /*查询小区
     * By yinyue
     * 2017--12-11
     */

    public static  function getSearch($data){


        $hr = DB::table('t_house_type')
            ->leftjoin('t_housing_resources','t_house_type.id','=','t_housing_resources.type_id')
            ->where('t_house_type.type_name','like','%'.$data['type_name']  .'%')
            ->get();
        return  $hr;
    }
   

    public static function getSearchHr($data){
        $hr = DB::table('t_house_type')
            ->leftjoin('t_housing_resources','t_house_type.id','=','t_housing_resources.type_id');

        if (array_key_exists('address', $data)) {
            $hr = $hr->where('address','like','%'.$data['address'].'%');
        }
        if (array_key_exists('price', $data)) {
            $hr = $hr->where('price','like','%'.$data['price'].'%');
        }
        if (array_key_exists('size', $data)) {
            $hr = $hr->where('size','like','%'.$data['size'].'%');
        }


        $hr = $hr->get();

        return $hr;
    }


    public static function getHouseReview($data){
        $bkhs = new HRR(); 
         // $bkh = HR::find($id);  
         //  $bkhs = HRR::find($bkh->id);    
       $bkhs->comment = $data['comment'];
        $bkhs->user_id = $data['user_id'];
         $bkhs->house_id = $data['house_id'];
       $bkhs->save();
         return $bkhs;
    }


//根据id获取小区楼盘参数信息
/*By yinyue
 *
 * 2017-11-28
 */
    public static function getHDById($id){
//       $hrs = DB::table('t_housing_resources')->leftJoin('t_house_detail','t_housing_resources.id','=','t_house_detail.house_id')->find($id);

        $hrs = HR::find($id);

        $hrd = HRD::find($hrs->id);

       // $hrs = HR::join()
        return $hrd;
    }

    /*根据小区楼盘获取相对应的户型推荐
     * By yinhue
     * 2017-11-29
     */

    public  static  function getHXById($house_id){
//
        $hx = DB::table('t_apartment_building')->where('house_id','=',$house_id)->get();
     return  $hx;

    }

    /*根据房源小区id以及客户的id获取客户对小区的评价
     * By yinyue
     *
     * 2017-11-30
     */

   public static function  getHCById($house_id){
//       $hrs = DB::table('t_housing_resources')->leftJoin('t_house_detail','t_housing_resources.id','=','t_house_detail.house_id')->find($id);
      $hc = DB::table('t_house_review')->where('house_id','=',$house_id)->get();
      return $hc;
   }




    /*
     * 设置广告信息，用于编辑、
     *
     * By TerryQi
     *
     */
    public static function setHR($hrs, $data)
    {
        if (array_key_exists('title', $data)) {
            $hrs->title = array_get($data, 'title');
        }
        if (array_key_exists('image', $data)) {
            $hrs->image = array_get($data, 'image');
        }
        if (array_key_exists('url', $data)) {
            $hrs->url = array_get($data, 'url');
        }
        if (array_key_exists('address', $data)) {
            $hrs->address = array_get($data, 'address');
        }
        if (array_key_exists('seq', $data)) {
            $hrs->seq = array_get($data, 'seq');
        }

        if (array_key_exists('status', $data)) {
            $hrs->size = array_get($data, 'size');
        }

        if (array_key_exists('status', $data)) {
            $hrs->price = array_get($data, 'price');
        }

        if (array_key_exists('status', $data)) {
            $hrs->label = array_get($data, 'label');
        }
        if (array_key_exists('status', $data)) {
            $hrs->remain = array_get($data, 'remain');
        }
        if (array_key_exists('status', $data)) {
            $hrs->status = array_get($data, 'status');
        }
        return $hrs;
    }
}