<?php

return [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id'  => env('WECHAT_APPID', 'wx85a771d5dac6c0d0'),         // AppID
    'secret'  => env('WECHAT_SECRET', '05dc14834a17fab400da0b532fc8657f'),     // AppSecret
    'token'   => env('WECHAT_TOKEN', '3Eowbj5dA4zkbprvK3nnp3NDz/pj6TSVdyVO+b/AX90='),          // Token
    'aes_key' => env('WECHAT_AES_KEY', ''),                    // EncodingAESKey

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'error'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],
    /*
     * 小程序配置
     * */
    "mini"=>[
		'1'=>[
			'app_id' => 'wx784738de34f8f5d6',
			'secret' => '78050e466186eec8bcb9c1eace77d245',
			// 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
			'response_type' => 'array',
			//商户号、api秘钥
			'mch_id' =>'1509666461',
			'key'    =>'0A36F242715949748F648178531EE85F',
			'cert_path'=> './apiclient_cert.pem', 
			'key_path' => './apiclient_key.pem',   
			//回调地址
			'notify_url'=>'',
			'log' => [
				'level' => 'debug',
				'file' => storage_path('logs/mini_1.log'),
			],
			'template_id'=>'_fa2cDJVj6QNuobc_gKCj_tilgw6MnWiW4j5q_WR0eo' //消息通知模板
		],
		'2' =>[
			'app_id' =>'wx2c7959c3c45150b2',
            'secret' =>'c1909a41aa602373f4b2178e7b4ca515',

            //回调地址
            'notify_url'=>'',
            'log' => [
                'level' => 'error',
                'file' => storage_path('logs/mini_2.log'),
            ],
			'template_id'=>'',
		]
	],
	
	

    /*
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    // 'oauth' => [
    //     'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
    //     'callback' => env('WECHAT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
    // ],

    /*
     * 微信支付
     */
    // 'payment' => [
    //     'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
    //     'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //     'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
    //     'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
    //     // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
    //     // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
    //     // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
    //     // ...
    // ],

    /*
     * 开发模式下的免授权模拟授权用户资料
     *
     * 当 enable_mock 为 true 则会启用模拟微信授权，用于开发时使用，开发完成请删除或者改为 false 即可
     */
    // 'enable_mock' => env('WECHAT_ENABLE_MOCK', true),
    // 'mock_user' => [
    //     "openid" =>"odh7zsgI75iT8FRh0fGlSojc9PWM",
    //     // 以下字段为 scope 为 snsapi_userinfo 时需要
    //     "nickname" => "overtrue",
    //     "sex" =>"1",
    //     "province" =>"北京",
    //     "city" =>"北京",
    //     "country" =>"中国",
    //     "headimgurl" => "http://wx.qlogo.cn/mmopen/C2rEUskXQiblFYMUl9O0G05Q6pKibg7V1WpHX6CIQaic824apriabJw4r6EWxziaSt5BATrlbx1GVzwW2qjUCqtYpDvIJLjKgP1ug/0",
    // ],
];
