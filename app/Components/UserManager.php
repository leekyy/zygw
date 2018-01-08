<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;
use App\Models\Exange;
use App\Models\User;
use App\Models\Goods;
use App\Models\Rules;
use Illuminate\Support\Facades\DB;
use App\Models\Pingjia;

class UserManager
{

    /*
     * 根据id获取用户信息，带token
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserInfoByIdWithToken($data)
    {
        $user = User::find($data['id']);
        return $user;
    }

    /*
     * 根据id获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserInfoById($id)
    {
        $user = User::where('id','=',$id)->first();
        if ($user) {
            $user->token = null;
        }
        return $user;
    }

    /*
     * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
     *
     * By TerryQi
     *
     * 2017-09-14
     *
     * 返回值
     *
     */
    public static function checkToken($id, $token)
    {
        //根据id、token获取用户信息
        $count = User::where('id', '=', $id)->where('token', '=', $token)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*获取用户的佣金
     * By yinyue
     *
     * 2017-12-5
     */

    public static  function  getUserYongjin($id){
        $user = User::find($id);
        return $user;

    }

    /*获取积分商品
     * By yinyue
     *2017-12-6
     */
     public static  function  getGoods(){
         $good = Goods::where('state',"=",'0')->get();
         return $good;
     }

    public static function  getGoodById($data){
        $id = $data['id'];
        $hr = Goods::find($id);
        return $hr;
    }


     /*获取用户的兑换记录
      * By yinyue
      * 2017-12-22
      */
     public static  function getExchange($data){
        //var_dump($good);
        //$goods = new Exange();

        $good = DB::table('t_goods_information')
        ->where('id','=',$data['id'])
        ->where('state','=',0)
        ->first();


        $user = DB::table('t_user_info')
        ->where('id','=',$data['user_id'])
        ->where('jifen','>=',$good->point)
        ->first();

        if(empty($user)){
            return false;
        }else{
             $p = $user->jifen - $good->point;
             $rs = DB::table('t_user_info')
             ->where('id','=',$data['user_id']) 
             ->update(['jifen'=>$p]);   
        }

         
        // $user = DB::table('t_goods_exchange')
        // ->get();

        $goods = DB::table('t_goods_exchange')
        ->insert(['goods_id'=>$good->id,'user_id'=>$user->id
            ,'created_at'=>date('Y-m-d H:i:s')]);
        return $goods;
       /* $goods->goods_id = $data['goods_id'];
        $goods->goodsname = $data['goodsname'];
         $g= Exange::where('id','=',$id)->get();*/
        //$goods->save();
        //return $good;
     }



     //用户积分加10\
     public static function getUserJifen($data){
       $user =DB::table('t_user_info')->where('id','=',$data['id'])->first();
        if($user->qiandao_time == ''){
            $user->qiandao_time = 0;
        }
        if(date('Y-m-d') != 
            date('Y-m-d',strtotime($user->qiandao_time))){
            $jifen =$user->jifen+10;
            $qiandao_time =date('Y-m-d H:i:s');
            $rs = DB::table('t_user_info')
             ->where('id','=',$data['id']) 
             ->update(['jifen'=>$jifen,'qiandao_time'=>$qiandao_time]); 

             $user->jifen = $user->jifen+10;

             $user =  json_decode( json_encode( $user),true);
             $rs = ['code'=>true,'user'=>$user];
             return $rs;

        }else{
            $user =  json_decode( json_encode( $user),true);
            $rs = ['code'=>false,'user'=>$user];
            return $rs;
        }
        
     }

    /*获取积分规则
   * By yinyue
   *2017-12-7
   */
    public static  function  getRules(){
        $rules = Rules::where('state',"=",'0')->get();
        return $rules;
    }

    /*获取客户对我的接待评价
  * By yinyue
  *2017-12-7
  */
    public static  function  getPingjia(){
//        $hrs = DB::table('t_housing_resources')->leftJoin('t_house_detail','t_housing_resources.id','=','t_house_detail.house_id')->find($id);
        $rules = DB::table('t_client_data')->join('t_reception_evaluation','t_client_data.id','=','t_reception_evaluation.kehu_id')->get();
        return $rules;
    }

    public static function getHezuo(){
        $user = DB::table('t_cooperation_rules')
        ->where('state','=',0)
        ->get();
        return $user;

    }
   
    public static function getWhitebook(){
        $user = DB::table('t_white_book')
        ->where('state','=',0)
        ->get();
        return $user;

    }


/*获取成交客户的房贷信息
 * By yinyue
 * 2017-12-8
 */

 public static  function  getJisuanqi(){






 }


    /*
     * 用户登录
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function login($data)
    {
        //获取account_type，后续进行登录类型判断
        $account_type = $data['account_type'];
        // 判断小程序，按照类型查询
        if ($account_type === 'xcx') {
            $user = self::getUserByXCXOpenId($data['xcx_openid']);
            //存在用户即返回用户信息
            if ($user != null) {
                return $user;
            }
        }
        //不存在即新建用户
        return self::register($data);
    }

    /*
     * 配置用户信息，用于更新用户信息和新建用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function setUser($user, $data)
    {
        if (array_key_exists('nick_name', $data)) {
            $user->nick_name = array_get($data, 'nick_name');
        }
        if (array_key_exists('avatar', $data)) {
            $user->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $user->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('xcx_openid', $data)) {
            $user->xcx_openid = array_get($data, 'xcx_openid');
        }
        if (array_key_exists('token', $data)) {
            $user->token = array_get($data, 'token');
        }
        if (array_key_exists('unionid', $data)) {
            $user->unionid = array_get($data, 'unionid');
        }
        if (array_key_exists('gender', $data)) {
            $user->gender = array_get($data, 'gender');
        }
        if (array_key_exists('status', $data)) {
            $user->status = array_get($data, 'status');
        }
        if (array_key_exists('type', $data)) {
            $user->type = array_get($data, 'type');
        }
        if (array_key_exists('province', $data)) {
            $user->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $user->city = array_get($data, 'city');
        }
        if (array_key_exists('email', $data)) {
            $user->email = array_get($data, 'email');
        }
        if (array_key_exists('cardID', $data)) {
            $user->cardID = array_get($data, 'cardID');
        }
        if (array_key_exists('tuijian', $data)) {
            $user->tuijian = array_get($data, 'tuijian');
        }
        if (array_key_exists('yongjin', $data)) {
            $user->yongjin = array_get($data, 'yongjin');
        }
        if (array_key_exists('role', $data)) {
            $user->role = array_get($data, 'role');
        }




        return $user;
    }

    /*
     * 注册用户
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function register($data)
    {
        //创建用户信息
        $user = new User;
        //account是必填项目
        if (array_key_exists('account_type', $data)) {
            $user->account_type = array_get($data, 'account_type');
        } else {
            return null;
        }
        $user = self::setUser($user, $data);
        $user->token = self::getGUID();
        $user->save();
        $user = self::getUserInfoByIdWithToken($user->id);
        return $user;
    }

    /*
     * 更新用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function updateUser($data)
    {
        //配置用户信息
        $user = UserManager::getUserInfoByIdWithToken($data);
      $user = self::setUser($user, $data);
       $user->save();
        return $user;
    }


    /*
     * 根据用户openid获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserByXCXOpenId($openid)
    {
        $user = User::where('xcx_openid', '=', $openid)->first();
        return $user;
    }


    // 生成guid
    /*
     * 生成uuid全部用户相同，uuid即为token
     *
     */
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }
}