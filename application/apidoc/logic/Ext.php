<?php

    /**
     * @api {post} ext/getLoginAd 得到填写手机号页面的广告
     * @apiVersion 1.0.0
     * @apiName getLoginAd
     * @apiDescription 得到填写手机号页面的广告
     * @apiGroup Ext
     *
     * @apiSuccess {String} content 图片url，比如http://www.baidu.com/image.jpg
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "content": "http://www.baidu.com/image.jpg"
    }
    }
     * @apiUse CreateUserError
     */
