<?php

namespace common\models;

use core\models\HanGreatLogger;
use OSS\Core\OssException;
use OSS\OssClient;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\Response;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class Base extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_del' => true //这里一定要设置你自己的字段
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method, 是不是用修改原生的delete方法. 如果不是, 就remove掉这行
            ],
        ];
    }

    /**
     * CURL模拟GET与POST请求
     * @param $url string 请求地址
     * @param $method string 请求方式 get post
     * @param $params array 数据
     * @param $paramsType string 请求数据格式
     * @param $timeOut int cURL最大执行时间
     * @return mixed
     * */
    public static function httpRequest($url, $method, $params = array(), $paramsType = 'http', $timeOut = 30)
    {
        if (trim($url) == '' || !in_array($method, array('get', 'post', 'url'))
//            || !is_array($params)
            || !in_array($paramsType, array('http', 'array', 'json'))
            || !is_numeric($timeOut) || $timeOut <= 0
            // get请求时，paramsType只能是http
            || ($method == 'get' && $paramsType != 'http')
        ) {
            return false;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //是否为https请求
        if (strncasecmp($url, 'https://', 8) === 0 ? TRUE : FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //https请求，设定为不验证证书和host
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $headers = [];

        switch ($paramsType) {
            case 'http':
                $params = http_build_query($params);
                break;
            case 'array':
//                $params = $params;
                break;
            case 'json':
                $headers[] = 'content-type: application/json';
                $params = json_encode($params);
                break;
            default:
                $result = '';
                break;
        }

        switch ($method) {
            case 'url':
//                $url = $url;
                break;
            case 'get':
                $url = $url . '?' . $params;
                break;
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default:
                $result = '';
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($curl, CURLOPT_USERAGENT, 'fblife');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if (isset($result)) {
        } else {
            $result = curl_exec($curl);
        }
        curl_close($curl);
//        var_dump($result);
        return $result;
    }

    public static function getIsNotDelOptions()
    {
        return ['is_del' => 0];
    }

    /**
     * @return bool
     * 重写作废影响更新时间
     */
    public function delete(){
        $this->is_del = 1;
        return $this->save(false, ['updated_at', 'is_del']);
    }


    /**
     * 上传 阿里云 oss
     * @param null $filename
     * @param null $tmp_name
     * @return string
     */
    public static function uploadCloud($tmp_name, $filename, $alias)
    {
        //上传文件
        if (empty($tmp_name) || empty($filename)) {
            return '';
        }

        $url = '';
        $pinfo = pathinfo($filename);
        $ftype = $pinfo['extension'];
        $orgfilename = $pinfo['basename'];
        $rename = $alias . DIRECTORY_SEPARATOR . date("Ymd") . DIRECTORY_SEPARATOR . date("His") . '_' . substr(md5($orgfilename), 12, 10) . '.' . $ftype;

        try {
            $class = new OssClient(Yii::$app->params['alioss']['access_id'], Yii::$app->params['alioss']['access_key'], Yii::$app->params['alioss']['endpoint'], false);
            $data = $class->multiuploadFile(Yii::$app->params['alioss']['bucket'], $rename, $tmp_name, []);
            $url = $data["oss-request-url"];
        } catch (OssException $e) {
            var_dump($e->getMessage());
            exit;
        }

        return $url;
    }

    /**
     * 删除 阿里云 oss 图片
     * @param $filename
     */
    public static function deleteCloud($filename)
    {
        if (!empty($filename)) {
            try {
                $class = new OssClient(Yii::$app->params['alioss']['access_id'], Yii::$app->params['alioss']['access_key'], Yii::$app->params['alioss']['endpoint'], false);
                $class->deleteObject(Yii::$app->params['alioss']['bucket'], $filename);
            } catch (OssException $e) {
                var_dump($e->getMessage());
                exit;
            }
        }
    }

    /**
     * 提示跳转
     */
    public static function hintHtml($url = null)
    {
        $url = is_null($url) ? Url::to(['index']) : $url;
        $html = "<html><head><style>#out{width:500px;height:250px;margin:0 auto;margin-top:150px;border:1px solid #ddd;text-align: center;} .title{height:35px;line-height: 35px;color:#fff;text-align: left;text-indent:1em;font-size:16px;background-color: #00a157;} h2{padding-top:40px;} .p1{margin-top: 40px;}</style></head><body>";
        $html .= "<div id='out'><div class='title'>友情提示</div><h2>当前账号没有设置基地信息</h2><p class='p1'>注:未分配基地的账号,不能创建信息.</p><p class='p2'>系统将在1秒钟返回首页,如果系统未自动跳转,请点击<a href='" . $url . "'>这里</a>!</p></div>";
        $html .= "</body><script>setTimeout(function(){location='" . $url . "'},2000)</script></html>";
        echo $html;
        exit;
    }

    /**
     * 错误提示
     * @param $msg
     * @param $url
     * @param int $time
     */
    public static function error($msg = null, $url = null, $time = 3000)
    {
        $msg = is_null($msg) ? '错误提示' : $msg;
        $url = is_null($url) ? Url::to(['index']) : $url;
        $html = "<html>";
        $html .= "<head>";
        $html .= "<style>h1{font-size: 30px;padding-top: 30px;} #out{width:500px;height:250px;margin:0 auto;margin-top:150px;border:1px solid #ddd;text-align: center;} .title{height:35px;line-height: 35px;color:#fff;text-align: left;text-indent:1em;font-size:16px;background-color: #00a157;} h2{padding-top:40px;} .p1{margin-top: 40px;}</style>";
        $html .= "</head>";
        $html .= "<body>";
        $html .= "<div id='out'>";
        $html .= "<div class='title'>提示信息</div>";
        $html .= "<h1>" . $msg . "  :(</h1>";
        $html .= "<p class='p1'>系统将在" . ($time / 1000) . "秒钟返回首页,如果系统未自动跳转,请点击<a href='" . $url . "'>这里</a>!</p>";
        $html .= "</div>";
        $html .= "</body>";
        $html .= "<script>setTimeout(function(){location='" . $url . "'}," . $time . ")</script>";
        $html .= "</html>";
        echo $html;
        exit;
    }

    /**
     * 金额转换小数,分转换元
     * @param int $number
     * @return string
     */
    public static function moneyBcdiv($number = 0, $len = 2)
    {
        return bcdiv($number, 100, $len);
    }

    /**
     * 金额转换整数,元转换分
     * @param int $number
     * @return string
     */
    public static function moneyBcmul($number = 0)
    {
        return bcmul($number, 100, 0);
    }

    /**
     * 元变为万元
     * @param int $number
     * @param int $len
     * @return string
     */
    public static function yuanToWan($number = 0, $len = 2)
    {
        return bcdiv($number, 10000, $len);
    }

    /**
     * 将万元变为元
     * @param int $number
     * @return string
     */
    public static function wanToYuan($number = 0)
    {
        return bcmul($number, 10000, 0);
    }

    /**
     * 重量转换小数,克转换千克
     * @param int $number
     * @return string
     */
    public static function weightBcdiv($number = 0, $len = 2)
    {
        return bcdiv($number, 1000, $len);
    }

    /**
     * 重量转换整数,千克转换克
     * @param int $number
     * @return string
     */
    public static function weightBcmul($number = 0)
    {
        return bcmul($number, 1000, 0);
    }

    /**
     * 重量转换小数,克转换吨
     * @param int $number
     * @return string
     */
    public static function weightToTonBcdiv($number = 0)
    {
        return bcdiv($number, 1000000, 2);
    }

    /**
     * 重量转换整数,吨转换克
     * @param int $number
     * @return string
     */
    public static function weightToTonBcmul($number = 0)
    {
        return bcmul($number, 1000000, 0);
    }

    /**
     * 天变为秒
     * @param int $number
     * @return string
     */
    public static function dayToSeconds($number = 0)
    {
        return bcmul($number, 3600 * 24, 0);
    }

    /**
     * 秒变为天
     * @param int $number
     * @return string
     */
    public static function secondsToDay($number = 0)
    {
        return bcdiv($number, 3600 * 24, 0);
    }

    /**
     * 字符串在网页上多行显示,将换行符替换为<br>
     * @param string $text
     * @return string
     */
    public static function textMultiline($text)
    {
        return str_replace(PHP_EOL, '<br>', $text);
    }

    /**
     * 求两个日期之间相差的天数(不考虑时间部分)
     * @param int $date1
     * @param int $date2
     * @return number
     */
    function diff_date($date1, $date2)
    {
        $startTime = strtotime(date("Y-m-d", $date1));
        $endTime = strtotime(date("Y-m-d", $date2));
        $diff = $startTime - $endTime;
        $day = $diff / 86400;
        return intval($day);
    }

    /**
     * 用PDF打印单据内容和单据条形码
     * @param int $filename
     * @param array $html
     * @param int $barcode
     * @param int $size
     */
    public function html($filename = null, $html = array(), $barcode = '', $size = 14)
    {
        $pdf = (new \TCPDF('L', 'mm', array(240, 140), true, 'UTF-8', false));
        // define barcode style
        $style = array(
            'position' => 'C',
            'align' => '',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 0,
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        // set margins
        $pdf->SetMargins(5, 1, 5);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('stsongstdlight', '', $size);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->write1DBarcode($barcode, 'C128', '', 160, '', 18, 1, $style, 'N');
        $pdf->lastPage();
        $pdf->Output($filename, 'I');
    }

    /**
     * 根据字符串获取适合在商品标签上显示的文字的字体
     * @param $string
     * @return string
     */
    public static function getGoodCardFontSize($string)
    {
        $size = '3.4mm';
        $len = mb_strlen($string, 'UTF8');
        if ($len > 11) {
            $size = '2mm';
        } else if ($len == 11) {
            $size = '2.1mm';
        } else if ($len == 10) {
            $size = '2.4mm';
        } else if ($len == 9) {
            $size = '2.6mm';
        } else if ($len == 8) {
            $size = '3mm';
        }

        return $size;
    }

    /**
     * 根据字符串获取适合在商品标签上显示的基地名称文字的字体
     * @param $string
     * @return string
     */
    public static function getProduceNameGoodCardFontSize($string)
    {
        $size = '3.4mm';
        $len = mb_strlen($string, 'UTF8');
        if ($len > 22) {
            $size = '2.7mm';
        } else if ($len > 20) {
            $size = '2.8mm';
        } else if ($len > 18) {
            $size = '3.1mm';
        }

        return $size;
    }

    /**
     * CDN 缩放图片
     * @param $string
     * @return string
     */
    public static function imageTrasferSize($imageURl, $width, $height){
        if($imageURl){
            if(stripos($imageURl,"clouddn")!== false){
                $imageURl = $imageURl.'?imageView2/0/w/'.$width.'/h/'.$height;
            }else{
                $imageURl = $imageURl.'?x-oss-process=image/resize,m_lfit,h_'.$height.',w_'.$width;
            }
        }
        return $imageURl;
    }

    /**
     * 数组分页函数 (核心函数 array_slice)
     * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
     * @param $count 每页多少条数据
     * @param $page 当前第几页
     * @param $array 查询出来的所有数组
     * @param $order 0 - 不变   1- 反序
     * @return array
     */
    public static function page_array($count,$page,$array,$order=0)
    {
        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
        $start=($page-1)*$count; #计算每次分页的开始位置
        if($order==1){
            $array=array_reverse($array);
        }
        $totals=count($array);
        $countpage=ceil($totals/$count); #计算总页面数
        $pagedata=array();
        $pagedata=array_slice($array,$start,$count);
        return ['list' => $pagedata, 'total' => $totals, 'pageSize' => $count, 'current' => $page]; #返回查询数据
    }

    /**
     * HTTP发送原始数据
     * @param $data
     * @param null $sourceData 源数据（如果发送的数据被加密过的话）
     * @param string $format 发送格式
     * @return \yii\console\Response|Response
     */
    public static function sendRawData($data, $sourceData = null, $format = Response::FORMAT_JSON)
    {
        $response = Yii::$app->response;
        $response->format = $format;
        $response->data = $data;

        // 用户未登录修改返回错误码
        if(in_array($data['code'], [200110, 200110, 200111, 200112, 200125])) {
            $response->statusCode = 401;
        }

//        // 允许跨域访问
//        if(isset($_SERVER['HTTP_ORIGIN'])) {
//            $domain = $_SERVER['HTTP_ORIGIN'];
//        } else {
//            $domain = '*';
//        }
////        $domain = 'http://localhost:8000';
//
//        //if (count($args) > 5) { $response->setStatusCode($value); }
//        $response->headers->set("Access-Control-Allow-Origin", $domain);
//        $response->headers->set("Access-Control-Allow-Headers","Origin, Accept-Language, Accept-Encoding, X-Forwarded-For, Connection, Accept, User-Agent, Host, Referer, Cookie, Content-Type, Cache-Control, X-Requested-With");
//        $response->headers->set("Access-Control-Allow-Methods","POST,GET,PUT,HEAD,OPTIONS");
//        $response->headers->set("Access-Control-Allow-Credentials","true");
//        $response->headers->set("Access-Control-Max-Age",3600*24*20);
//
//        $requestUri = explode('?', $_SERVER['REQUEST_URI']);
//        $requestUrl = isset($requestUri[0]) ? $requestUri[0] : '';
//        $requestMethod = $_SERVER['REQUEST_METHOD'];
//        if($requestMethod == 'GET'){
//            $data = isset($requestUri[1]) ? $requestUri[1] : '';
//        }else{
//            $data = Yii::$app->request->post() or $data = json_decode(Yii::$app->request->getRawBody());
//            if(is_array($data)){
//                $data = http_build_query($data);
//            }
//        }


        $dataStr = '';
        if ($sourceData == null) {
            if (is_array($data)) {
//                $dataStr = str_replace(array("\'", "\"", ',', ':'), array('', '', '&', '='), json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                $dataStr = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            else {
                $dataStr = $data;
            }
        }
        else {
//            $dataStr = str_replace(array("\'", "\"", ',', ':'), array('', '', '&', '='), json_encode($sourceData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            $dataStr = json_encode($sourceData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $isSuccess = true;
        if (is_array($data) && array_key_exists('code', $data)) {
            if ($data['code'] !== 10000) {
                $isSuccess = false;
            }
        }
        if (is_array($data) && array_key_exists('status', $data)) {
            if ($data['status'] !== true) {
                $isSuccess = false;
            }
        }

        if($isSuccess === true){
            HanGreatLogger::log('接口返回：'.$dataStr);
        }else{
            HanGreatLogger::log('接口返回：'.$dataStr, Logger::LEVEL_WARNING);
        }

        return $response;
    }

    /**
     * 把对象转化成普通的数组：
     * @param $object
     * @return mixed
     */
    public static function object2array($object)
    {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } elseif (empty($object)){
            $array = [];
        } else {
            $array = $object;
        }
        return $array;
    }

    public function notNullValidate($attribute,$param){
        if(!isset($this->$attribute) || $this->$attribute==null){
            $this->addError($attribute, $param[$attribute]);
        }
    }

    public function notNullRelativeInfoValidate($attribute,$param){
        if(isset($this->$attribute)){
            $relative_info=$param[$attribute];
            $relative_data=$relative_info['relative_service']::$relative_info['relative_function']($this->$attribute);
            if($relative_data['code']!=200000){
                $this->addError($attribute, $relative_info["error_value"]);
            }
        }
    }

    public function dateFormateValidate($attribute,$param){
        if(isset($this->$attribute)){
            if(!strtotime($this->$attribute)) {
                $this->addError($attribute, $param[$attribute]);
            }
        }
    }
}
