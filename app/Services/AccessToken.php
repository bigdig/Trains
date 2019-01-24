<?php
/**
 * Created by PhpStorm.
 * User: lhf
 * Date: 2019/1/24
 * Time: 11:53
 */
namespace App\Services;
use Cache;
use EasyWeChat\Factory;

class AccessToken{
    protected $config;
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
        $this->config =config('wechat.mini')[$this->client];
    }
    public function getAccessToken($cache=true){
        $app = Factory::officialAccount($this->config);
        $accessToken = $app->access_token;
        if(!$cache){
            $token = $accessToken->getToken(true);
            Cache::setDefaultCacheTime(60)->forever($this->client.'_access_token',$token);
        }else{
            if( Cache::get($this->client.'_access_token') ){
                $token = $accessToken->getToken();
            }else{
                $token = $accessToken->getToken(true);
                Cache::setDefaultCacheTime(60)->forever($this->client.'_access_token',$token);
            }
        }
        return $token;
    }
}
