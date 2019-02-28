<?php

    /**
     * @api {post} active/getActiveList 得到活动列表
     * @apiVersion 1.0.0
     * @apiName getActiveList
     * @apiDescription 得到活动列表
     * @apiGroup Active
     *
     * @apiParam {Number} [pageIndex] 页码
     * @apiParam {Number} [pageItemCount] 每页条数
     * @apiParamExample {json} 发送报文:
    {
    "pageIndex":1,
    "pageItemCount":0
    }
     *
     * @apiSuccess {Object[]} activeList 活动列表
     * @apiSuccess {Number} subjectList.activeID 活动ID
     * @apiSuccess {String} subjectList.title 活动标题
     * @apiSuccess {String} subjectList.note 备注
     * @apiSuccess {String} subjectList.thumbPc Pc标题图片地址
     * @apiSuccess {String} subjectList.thumbApp App标题图片地址
     * @apiSuccess {String} subjectList.linkPc Pc跳转地址
     * @apiSuccess {String} subjectList.linkApp App跳转地址
     * @apiSuccess {String} subjectList.addTime 添加时间
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "activeList": [
    {
    "activeID": 1,
    "title": "阿斯顿发生掉发",
    "note": "阿斯顿发生掉发阿斯顿发生掉发阿斯顿发生掉发",
    "thumbPc": "",
    "thumbApp": "",
    "linkPc": "",
    "linkApp": "",
    "addTime": "2017-12-23 14:02:09"
    },
    {
    "activeID": 2,
    "title": "今日活动",
    "note": "今日活动今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈",
    "thumbPc": "http://slb.dahengdian.com/51zhangxi/2017/12/23/o06Dih24iH.png",
    "thumbApp": "http://slb.dahengdian.com/51zhangxi/2017/12/23/5bWVyqXAdq.jpg",
    "linkPc": "122222222222222222",
    "linkApp": "1111111111111111111",
    "addTime": "2017-12-23 14:02:58"
    }
    ],
    "count": 2,
    "pageItemCount": 10
    }
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} active/getActiveList 得到活动列表
     * @apiVersion 1.0.1
     * @apiName getActiveList
     * @apiDescription 得到活动列表
     * @apiGroup Active
     *
     * @apiParam {Number} [pageIndex] 页码
     * @apiParam {Number} [pageItemCount] 每页条数
     * @apiParamExample {json} 发送报文:
    {
    "pageIndex":1,
    "pageItemCount":0
    }
     *
     * @apiSuccess {Object[]} activeList 活动列表
     * @apiSuccess {Number} subjectList.activeID 活动ID
     * @apiSuccess {String} subjectList.title 活动标题
     * @apiSuccess {String} subjectList.note 备注
     * @apiSuccess {String} subjectList.thumbPc Pc标题图片地址
     * @apiSuccess {String} subjectList.thumbApp App标题图片地址
     * @apiSuccess {String} subjectList.linkPc Pc跳转地址
     * @apiSuccess {String} subjectList.linkApp App跳转地址
     * @apiSuccess {String} subjectList.addTime 添加时间
     * @apiSuccess {Object} subjectList.share 分享参数，如果没有分享功能，则该参数不返回
     * @apiSuccess {Object} subjectList.share.headImgUrl 分享图标
     * @apiSuccess {String} subjectList.share.title 分享标题
     * @apiSuccess {String} subjectList.share.desc 分享描述
     * @apiSuccess {String} subjectList.share.link 分享链接
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "activeList": [
    {
    "activeID": 1,
    "title": "阿斯顿发生掉发",
    "note": "阿斯顿发生掉发阿斯顿发生掉发阿斯顿发生掉发",
    "thumbPc": "",
    "thumbApp": "",
    "linkPc": "",
    "linkApp": "",
    "addTime": "2017-12-23 14:02:09",
    "share": {
    "headImgUrl":"https://static.qimai.cn/static/img/newaso100@2x.png",
    "title":"11334466778899",
    "desc":"11334466778899",
    "link":"http://www.qq.com"
    }
    },
    {
    "activeID": 2,
    "title": "今日活动",
    "note": "今日活动今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈今日活动哈哈",
    "thumbPc": "http://slb.dahengdian.com/51zhangxi/2017/12/23/o06Dih24iH.png",
    "thumbApp": "http://slb.dahengdian.com/51zhangxi/2017/12/23/5bWVyqXAdq.jpg",
    "linkPc": "122222222222222222",
    "linkApp": "1111111111111111111",
    "addTime": "2017-12-23 14:02:58"
    }
    ],
    "count": 2,
    "pageItemCount": 10
    }
    }
     * @apiUse CreateUserError
     */
