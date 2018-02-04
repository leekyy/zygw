<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use function GuzzleHttp\default_ca_bundle;
use Qiniu\Auth;

class SendMessageManager
{

    const CLIENT_COMMING = "CLIENT_COMMING";    //客户即将到访通知

    /*
     * 发送消息
     *
     * By TerryQi
     *
     */
    public static function sendMessage($user_id, $message_type, $message_content)
    {
        $user = UserManager::getUserInfoByIdWithToken($user_id);
        //判断服务号是否为空，如果不为空则通过服务号发送消息，如果为空则通过短信模板发送消息
        if (!Utils::isObjNull($user->fwh_openid)) {
            self::setMessageFromFWH($user->fwh_openid, $message_type, $message_content);
        } else {

        }

    }

    /*
     * 通过服务号发送消息
     *
     */
    private static function setMessageFromFWH($fwh_openid, $message_type, $message_content)
    {
        $message = self::getTemplateContentFromMessageType($message_type, $message_content);
        $template_id = self::getTemplateIdFromMessageType($message_type);
        WechatManager::sendTemplateMessage($fwh_openid, $template_id, $message);
    }

    /*
     * 通过短息下发
     *
     */
    private static function setMessageFromSM($phonenum, $message_type, $message_content)
    {
        $message = self::getSMSTemplateContentFromMessageType($message_type, $message_content);
        $template_id = self::getSMSTemplateIdFromMessageType($message_type);
        SMSManager::sendSMS($phonenum, $template_id, $message);
    }


    /*
     * 通过messageType和messageContent获取服务号模板类型
     *
     */
    private static function getTemplateContentFromMessageType($message_type, $message_content)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return [
                    'first' => '上报信息审核通过',
                    'keyword1' => $message_content["keyword1"],
                    'keyword2' => $message_content["keyword2"],
                    'keyword3' => DateTool::getCurrentTime(),
                    'remark' => '请在个人中心中查看'
                ];
            default:
                break;
        }
    }

    /*
     * 根据messageType获取模板id
     *
     */
    private static function getTemplateIdFromMessageType($message_type)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return "bVf14RC9Ts3gbqN2GMUg_czVoEXTtdV7WVtiu2DqKB0";
            default:
                break;
        }
    }


    /*
     * 通过messageType和messageContent获取服务号模板类型
     *
     */
    private static function getSMSTemplateContentFromMessageType($message_type, $message_content)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return $message_content['keyword1'] . ',' . $message_content['keyword2'] . ',' . $message_content['keyword3'] . ',' . $message_content['keyword4'];
            default:
                break;
        }
    }

    /*
     * 根据messageType获取短信模板id
     *
     */
    private static function getSMSTemplateIdFromMessageType($message_type)
    {
        //根据消息类型选择模板
        switch ($message_type) {
            case self::CLIENT_COMMING:  //客户即将到访
                return "170823977";
            default:
                break;
        }
    }

}