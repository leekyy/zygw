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
use App\Models\KH;
use App\Models\NEWs;
use Illuminate\Support\Facades\DB;


class KHManager
{

    /*
     * 获取报备过的客户信息
     *
     * By yinyue
     *
     * 2017-12-1
     *
     *
     */
    public static function  getKHs(){
        $hrs =DB::table('t_client_data')
            ->select('id','kehu_name','telephone','visitingstates','intent','created_at')
            ->get();
        return $hrs;
    }

    /*根据客户名字搜索客户
     * By yinyue
     * 2017-12-12
     */

    public static  function  getSearchKh($data){
        $kh = DB::table('t_client_data');

        if (array_key_exists('visitingstates', $data)) {
            $kh = $kh->where('visitingstates','like','%'.$data['visitingstates'].'%');
        }
        if (array_key_exists('kehu_name', $data)) {
            $kh = $kh->where('kehu_name','like','%'.$data['kehu_name'].'%');
        }

        // if(array_key_exists('visitingstate', $data)){
        //     if($data->visitingstate == 0){
        //         $kh->get();
        //     }
            
        //  }
         // var_dump($kh);


        $kh = $kh->get();

        return $kh;
    }

    /*2017-1-15
     * By Yinyue
     * 在客户列表页可以根据楼盘搜索客户
     */

    public static  function getSearchKhs($data){

        $kh  =DB::table('t_client_data')->where('t_client_data.intent','like','%'.$data['intent'].'%')->get();
        return $kh;

    }
    public static  function getKhIntent(){

        $kh  =DB::table('t_client_data')->select('intent')->get();
        return $kh;

    }


    /*获取前台提交传送过来的数据
     *
     * By yinyue
     * 2017-12-14
     */

    public  static  function  getBKH($data){
        $bkhs = new KH;
//        $bkh = DB::table('t_user_info')
//            ->leftjoin('t_client_data','t_user_info.id','=','t_client_data.user_id')
//           // ->where('t_user_info.id','=','t_client_data.user_id')
//            ->get();
       $bkhs->kehu_name = $data['kehu_name'];

       $bkhs->telephone = $data['telephone'];
        $bkhs->cartID = $data['cartID'];
        $bkhs->area = $data['area'];
        $bkhs->way = $data['way'];
        $bkhs->intent = $data['intent'];
        $bkhs->size = $data['size'];
        $bkhs->purpose = $data['purpose'];
       $bkhs->visitingstates = $data['visitingstates'];
       $bkhs->transactionstate = $data['transactionstate'];
       $bkhs->signingstate = $data['signingstate'];
       $bkhs->transactionmethod = $data['transactionmethod'];
        $bkhs->care = $data['care'];
        $bkhs->remark = $data['remark'];
        $bkhs->order = $data['order'];
       $bkhs->save();
        return $bkhs;
    }
 /*修改客户资料
  *
  * By yinyue
  * 2017-12-14
  */

    public static function getXKH($data){
        $xkh = KH::where('id','=',$data['id'])->first();
        if(array_key_exists('kehu_name', $data)){
            $xkh->kehu_name = $data['kehu_name'];
        }
        if(array_key_exists('telephone', $data)){
             $xkh ->telephone = $data['telephone'];
        }
        if(array_key_exists('cartID', $data)){
            $xkh ->cartID = $data['cartID'];
        }
        if(array_key_exists('area', $data)){
            $xkh ->area = $data['area'];
        }
        if(array_key_exists('way', $data)){
           $xkh ->way = $data['way'];
        }

        if(array_key_exists('intent', $data)){
           $xkh ->intent = $data['intent'];
        }
        if(array_key_exists('size', $data)){
            $xkh ->size = $data['size'];
        }
        if(array_key_exists('purpose', $data)){
             $xkh ->purpose = $data['purpose'];
        }
        if(array_key_exists('visitingstates', $data)){
            $xkh ->visitingstates = $data['visitingstates'];
        }
        if(array_key_exists('transactionstate', $data)){
             $xkh ->transactionstate = $data['transactionstate'];
        }
        if(array_key_exists('signingstate', $data)){
            $xkh ->signingstate = $data['signingstate'];
        }
        if(array_key_exists('transactionmethod', $data)){
            $xkh ->transactionmethod = $data['transactionmethod'];
        }
        if(array_key_exists('care', $data)){
            $xkh ->care = $data['care'];
        }
        if(array_key_exists('remark', $data)){
             $xkh ->remark = $data['remark'];
        }
        if(array_key_exists('order', $data)){
            $xkh ->order = $data['order'];
        }


      // $xkh ->telephone = $data['telephone'];
//        $xkh ->cartID = $data['cartID'];
//        $xkh ->area = $data['area'];
//        $xkh ->way = $data['way'];
//        $xkh ->intent = $data['intent'];
//        $xkh ->size = $data['size'];
//        $xkh ->purpose = $data['purpose'];
//        $xkh ->visitingstate = $data['visitingstate'];
//        $xkh ->transactionstate = $data['transactionstate'];
//        $xkh ->signingstate = $data['signingstate'];
//        $xkh ->transactionmethod = $data['transactionmethod'];
//        $xkh ->care = $data['care'];
//        $xkh ->remark = $data['remark'];
         $xkh->save();
        return $xkh;



    }

    /*获取消息
     * BY yinyue
     * 2017-12-5
     */
      public static  function getNEWs(){
          $hrs = DB::table('t_news')->select('*')->get();
          return $hrs;
      }




    /*
     * 根据id获取客户详细信息
     *
     * By yinyue
     *
     * 2017-12-4
     *
     *
     */
    public static function  getKHById($data){
        $id = $data['id'];
        $hr = KH::find($id);
        return $hr;
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