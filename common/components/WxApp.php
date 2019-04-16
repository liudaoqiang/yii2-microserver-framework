<?php

/**
 * Created by PhpStorm.
 * User: bozhang
 * Date: 2018/1/10
 * Time: 上午9:58
 */

namespace common\components;

use common\models\Base;
use restapi\controllers\ResponseCode;
use Yii;
use yii\base\Component;

class WxApp extends Component {

    private $SESSION_URL = "https://api.weixin.qq.com/sns/jscode2session";
    private $ACCESS_TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token";
    private $MESSAGE_URL = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send";
    private $WX_USER_INFO = "https://api.weixin.qq.com/cgi-bin/user/info";
    private $REDIS_SESSSION_PREFIX = 'WX_PLANT_APP:SESSION:';
    private $REDIS_ACCESSTOKEN_PREFIX = 'WX_APP:ACCESSTOKEN:';
    public $appId = null;
    public $appSecret = null;
    public static $OK = ResponseCode::SUCESS_CODE;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;

    public function __construct($wxappid, $appsecret) {
        $this->appId = $wxappid;
        $this->appSecret = $appsecret;

        return $this;
    }

    public function requestSession($code) {
        if (isset($this->appId) && isset($this->appSecret)) {
            $params = [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ];
            $dataStr = Base::httpRequest($this->SESSION_URL, 'get' , $params);
//            $dataStr = $this->request($this->SESSION_URL, $params);
            $data = json_decode($dataStr);
//            var_dump($data);die;
            if (isset($data->session_key)) {
//                $sessionId = $this->generateSessionId();
//
//                if ($this->saveWxAppInfo($sessionId, $dataStr, 24 * 60 * 60)) {
//                    $data->session_id = $sessionId;
                return json_decode(json_encode($data), true);
//                } else {
//                    return -1;
//                }
            } else {
                return ['data' => [], 'code' => ResponseCode::ERROR_CODE_USER_WX_GET_SESSION_KEY_FAIL, 'msg' => '微信登录获取session_key失败!'.$dataStr];
            }
        } else {
            return ['data' => [], 'code' => ResponseCode::ERROR_CODE_MISS_PARAM, 'msg' => '缺少参数'];
        }
    }

    public function sendServiceMessage($openId, $templateId, $formId, $templateData, $page = null, $color = null, $emphasisKeyword = null) {
        $accessToken = $this->getAccessToken();
        if (!empty($accessToken)) {
            $params = [
                "touser" => $openId,
                "template_id" => $templateId,
                "form_id" => $formId,
                "data" => $templateData
            ];
            if (!empty($page)) {
                $params["page"] = $page;
            }
            if (!empty($color)) {
                $params["color"] = $color;
            }
            if (!empty($emphasisKeyword)) {
                $params["emphasis_keyword"] = $emphasisKeyword;
            }
            $result = $this->request($this->MESSAGE_URL . '?access_token=' . $accessToken, $params, true);
            dump($result);
        }
    }

    public function getWxUserInfo($code) {
        $session = $this->requestSession($code);
        $accessToken = $this->getAccessToken();
        if (!empty($accessToken) && is_object($session)) {
            $params = [
                "openid" => $session->openid,
                "access_token" => $accessToken
            ];
            $dataStr = $this->request($this->WX_USER_INFO, $params);
            $data = json_decode($dataStr);
            return $data;
        } else {
            return -1;
        }
    }

    /*     * 获取用户手机号
     * {
      "phoneNumber": "13580006666",
      "purePhoneNumber": "13580006666",
      "countryCode": "86",
      "watermark":
      {
      "appid":"APPID",
      "timestamp":TIMESTAMP
      }
      }
     */

    public function getWxUserPhone($session_key, $encryptedData, $iv) {
        $resultData = $this->decryptData($session_key, $encryptedData, $iv);
		$array = json_decode(json_encode($resultData),true);
        if ($array["code"] == self::$OK) {
            return ['data' => $array['phoneInfo']['phoneNumber'], 'code' => $array["code"], 'mgs' => "查询手机号成功"];
        } else {
            return ['code' => $array["code"], "data" => $array, 'mgs' => "查询手机号成功"];
        }
    }

    private function getAccessToken() {
        $params = [
            "grant_type" => 'client_credential',
            "appid" => $this->appId,
            'secret' => $this->appSecret
        ];
        $accessToken = $this->getWxAppToken();
        if (empty($accessToken)) {
            $dataStr = $this->request($this->ACCESS_TOKEN_URL, $params);
            $data = json_decode($dataStr);
            if (isset($data->access_token)) {
                $this->saveWxAppToken($data->access_token, (int) ($data->expires_in - 20));
                return $data->access_token;
            } else {
                return undefined;
            }
        } else {
            return $accessToken;
        }
    }

    private function saveWxAppToken($accessToken, $tokenTTL) {
        return Yii::$app->redis->setex($this->REDIS_ACCESSTOKEN_PREFIX . $this->appId, $tokenTTL, $accessToken);
    }

    public function getWxAppToken() {
        $dataStr = Yii::$app->redis->get($this->REDIS_ACCESSTOKEN_PREFIX . $this->appId);
        return empty($dataStr) ? null : json_decode($dataStr);
    }

    private function saveWxAppInfo($sessionKey, $sessionValue, $sessionTTL) {
        return Yii::$app->redis->setex($this->REDIS_SESSSION_PREFIX . $sessionKey, $sessionTTL, $sessionValue);
    }

    public function getWxAppInfo($sessionKey) {
        $dataStr = Yii::$app->redis->get($this->REDIS_SESSSION_PREFIX . $sessionKey);
        return empty($dataStr) ? null : json_decode($dataStr);
    }

    private function request($url, $params, $isPost = false) {
        $ch = curl_init();
        //设置选项，包括URL
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, 1);  // post数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));    // post的变量
        } else {
            $url = $url . '?' . http_build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $response = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $response;
    }

    public function decryptData($sessionKey, $encryptedData, $iv) {
        $dataObj = ["code" => self::$OK];
        if (strlen($sessionKey) != 24) {
            $dataObj["code"] = self::$IllegalAesKey;
            return $dataObj;
        }
        $aesKey = base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            $dataObj["code"] = self::$IllegalIv;
            return $dataObj;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj["phoneInfo"] = json_decode($result);
        if (!$result) {
            $dataObj["code"] = self::$DecodeBase64Error;
            return $dataObj;
        }
        if ($dataObj["phoneInfo"]->watermark && $dataObj["phoneInfo"]->watermark->appid != $this->appId) {
            $dataObj["code"] = self::$IllegalBuffer;
            return $dataObj;
        }

        return $dataObj;
    }

    public function isSessionAvailable($sessionId) {
        return Yii::$app->redis->exists($this->REDIS_SESSSION_PREFIX . $sessionId) == 1;
    }

    private function generateSessionId() {
        return trim($this->randomFromDev(32));
    }

    public function randomFromDev($len) {
        $fp = @fopen('/dev/urandom', 'rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        } else {
            trigger_error('Can not open /dev/urandom.');
        }
        $result = base64_encode($result);
        $result = strtr($result, '+/', '-_');
        $result = str_replace('=', ' ', $result);
        return $result;
    }

}
