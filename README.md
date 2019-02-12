培训课程
====
mini_front 前端小程序

Laravel 做后端管理和API

概要说明
---
小程序培训报名+后端管理

功能说明
---
    1、实现了线上报名，线下参加培训的功能。 
    2、依据client不同，支持多客户端，绑定角色，实现分管。
    3、支持培训证书发放
    4、数据统计

部署安装
===

小程序
--
* 开发者工具打开mini_front
* 修改config.js文件的host,接口地址
* 小程序后台配置消息模板

后台部署
--
* 导入数据库train.sql
* 修改.env数据库配置
* 修改小程序配置和商户支付配置,/config/wechat.php mini项

参考
---
微信sdk

https://github.com/overtrue/laravel-wechat 

后台管理系统框架

https://github.com/DukeAnn/Laradmin/blob/master

https://lipis.github.io/bootstrap-sweetalert/

https://github.com/andersao/l5-repository

性能优化
====
    1、配置信息缓存
    php artisan config:cache //生成
    php artisan config:clear //取消
    2、路由缓存
    php artisan route:cache //生成
    php artisan route:clear //取消
    3、类映射缓存
    php artisan optimize
    php artisan clear-compiled
    4、自动加载
    composer dumpautoload -o
    5、关闭应用debug app.debug=false
    6、开启php7的OPcache扩展,配置如下
            opcache.memory_consumption=128
            opcache.interned_strings_buffer=8
            opcache.max_accelerated_files=4000
            opcache.revalidate_freq=60
            opcache.fast_shutdown=1
            opcache.enable_cli=1
    7、nginx开启gzip压缩
TIP
---
Email：lhf2008@yeah.net
