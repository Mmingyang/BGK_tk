<?php

//微信公众号开发
return [

    'wx' => [
        'debug' => true,
        'app_id' => 'wx55983decbd319957',
        'secret' => 'd40c1d64337d75513a304ec9ac1ba6cc',
        'token' => 'your-token',

        // 'aes_key' => null, // 可选

        'log' => [
            'level' => 'debug',
            'file' => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
        ],

        /**
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址
         */
        'oauth' => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => '/index/server/call',//授权之后跳哪里去
        ],

        /**
         * 微信支付
         */
        'payment' => [
            'merchant_id'        => 'your-mch-id',
            'key'                => 'key-for-signature',
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
            // 'device_info'     => '013467007045764',
            // 'sub_app_id'      => '',
            // 'sub_merchant_id' => '',
            // ...
        ],

        /**
         * Guzzle 全局设置
         *
         * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
         */
        'guzzle' => [
            'timeout' => 3.0, // 超时时间（秒）
            //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
        ],
    ],


];