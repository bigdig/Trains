<?php

namespace App\Services;
use Log;

class UmsApi{

//    const URL = 'http://ums-gateway.rybbaby.com/api/gateway';
    const TEST_URL = 'http://192.168.0.4:8180/api/gateway';

    protected $key = 'ums';

    public function __construct()
    {

    }

    /**
     * 推送培训信息
     */
    public function postTrain($data){
        $time = time();
        $randomStr = rand(1111,9999).rand(1111,9999);
        $gatewaySign = $this->sign([
            'signToken'=>$this->key,
            'randomStr'=>$randomStr,
            'timestamp'=>$time
        ]);

        $data['signToken'] = $this->key;
        $data['randomStr'] = $randomStr;
        $data['timestamp'] = $time;
        $data['gatewaySign'] = $gatewaySign;
        $url = self::TEST_URL.'/school/synSchoolTrainTime';
        $client = new \GuzzleHttp\Client();
        Log::error('ums post data :'.json_encode($data));
        $res = $client->request('POST', $url,[
            'form_params'=>$data
        ]);
        Log::error('ums response: status code:'.$res->getStatusCode().' content-type:'.$res->getHeaderLine('content-type').' data:'.$res->getBody());
        return $res;
    }
    /**
     * @param $data
     * 签名
     */
    private function sign($params){
        krsort($params);
        $signstr = '';
        foreach ($params as $key => $value) {
            if( is_array($value) ){
                $signstr .= $key .'='. json_encode($value).'&';
            }else{
                $signstr .= $key .'='. $value.'&';
            }
        }
        $signstr = rtrim($signstr,'&');
        $signstr = strtolower($signstr);
        $signstr = strrev($signstr);
        $signstr = $params['timestamp'].$signstr.$params['randomStr'];
        return md5($signstr);
    }
}