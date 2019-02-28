<?php


    /**
     * @api {post} system/getBankList 得到银行列表
     * @apiVersion 1.0.0
     * @apiName getUserBankList
     * @apiDescription 得到银行列表
     * @apiGroup System
     *
     *
     *
     * @apiSuccess {Object[]} bankList 第三方支付支持的银行卡列表
     * @apiSuccess {Number} bankList.bankPlatformID 第三方支付银行卡ID
     * @apiSuccess {String} bankList.alias 第三方支付银行卡代码
     * @apiSuccess {Number} bankList.isCredit 是否为信用卡,0-非信用卡，1为信用卡
     * @apiSuccess {Object} bankList.bank 银行
     * @apiSuccess {Number} bankList.bank.bankID 银行名称
     * @apiSuccess {String} bankList.bank.bankName 银行名称
     * @apiSuccess {String} bankList.bank.appIcon 银行app图标
     * @apiSuccess {String} bankList.bank.color 银行颜色（废弃）
     * @apiSuccess {String} bankList.bank.backgroundColor 银行背景色
     * @apiSuccess {String} bankList.bank.quotaTitle 限额名称描述
     * @apiSuccess {String} bankList.bank.quotaSingleOrder 单笔限额，单位元
     * @apiSuccess {String} bankList.bank.quotaSingleDay 单日限额
     * @apiSuccess {String} bankList.bank.quotaSingleMouth 单月限额
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "bankList": [
    {
    "bankPlatformID": 5,
    "alias": "张奇",
    "isCredit": "",
    "bank": {
    "bankID": 1,
    "bankName": "中国建设银行",
    "color": "#5991F0",
    "quotaTitle": "限额：",
    "quotaText": "单笔5千 单日五万 单月150万",
    "quotaSingleDay": 5000000,
    "quotaSingleOrder": 500000,
    "appIcon": "http://wxapp.dahengdian.com/static/mobile/image/zhongguo_icon2x.png",
    "pcIcon": "",
    "quotaSingleMonth": 500000000,
    "backgroundColor": "#5991F0"
    }
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} system/getConfig 得到系统配置
     * @apiVersion 1.0.0
     * @apiName getConfig
     * @apiDescription 得到系统配置
     * @apiGroup System
     *
     * @apiSuccess {Object} config 系统配置
     * @apiSuccess {Number} config.bankBandMax 至多绑定银行卡个数
     * @apiSuccess {Number} config.loanAgreementUrl 借款协议url
     * @apiSuccess {Number} config.riskBulletinUrl 风险揭示书url
     * @apiSuccess {String} config.rechargeUrl 跳转充值url
     * @apiSuccess {String} config.rechargeUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.rechargeUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.drawcashUrl 跳转提现url
     * @apiSuccess {String} config.drawcashUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.drawcashUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.userInvitationUrl 邀请注册url
     * @apiSuccess {String} config.activeUrl 活动url
     * @apiSuccess {String} config.customerUrl 客服url
     * @apiSuccess {String} config.aboutUsUrl 关于我们url
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "config": {
      "bankBandMax":1,
      "loanAgreementUrl":"http://www.baidu.com?######",
      "riskBulletinUrl":"http://www.baidu.com?######",
      "rechargeUrl":"http://wxapp.dahengdian.com/mobile/h5/userRecharge?######",
      "drawcashUrl":"http://wxapp.dahengdian.com/mobile/h5/userDrawcash?######",
      "userInvitationUrl":"http://www.baidu.com?######",
      "activeUrl":"http://www.baidu.com?######",
      "customerUrl":"http://www.baidu.com?######",
      "aboutUsUrl":"http://www.baidu.com?######"
     }
    }
    }
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} system/getConfig 得到系统配置
     * @apiVersion 1.0.1
     * @apiName getConfig
     * @apiDescription 得到系统配置
     * @apiGroup System
     *
     * @apiSuccess {Object} config 系统配置
     * @apiSuccess {String} config.startPageImage 动态启动页图片url（建议拉伸），建议保持缓存12小时
     * @apiSuccess {Number} config.bankBandMax 至多绑定银行卡个数
     * @apiSuccess {String} config.loanAgreementUrl 借款协议url
     * @apiSuccess {String} config.riskBulletinUrl 风险揭示书url
     * @apiSuccess {String} config.rechargeUrl 跳转充值url
     * @apiSuccess {String} config.rechargeUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.rechargeUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.rechargeWYUrl 跳转网银充值url
     * @apiSuccess {String} config.rechargeWYUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.rechargeWYUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.drawcashUrl 跳转提现url
     * @apiSuccess {String} config.drawcashUrl.--money app应用参数，
     *
     * 金额
     * @apiSuccess {Number} config.drawcashUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.userInvitationUrl 邀请注册url
     * @apiSuccess {String} config.activeUrl 活动url
     * @apiSuccess {String} config.customerUrl 客服url
     * @apiSuccess {String} config.aboutUsUrl 关于我们url
     * @apiSuccess {String} config.regAgreementUrl 注册协议url
     * @apiSuccess {String} config.stat 统计相关
     * @apiSuccess {String} config.stat.userRegCount 注册用户数
     * @apiSuccess {String} companyInfo 公司信息
     * @apiSuccess {String} companyInfo.name 公司名称
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "config": {
    "bankBandMax":1,
    "loanAgreementUrl":"http://www.baidu.com?######",
    "riskBulletinUrl":"http://www.baidu.com?######",
    "rechargeUrl":"http://wxapp.dahengdian.com/mobile/h5/userRecharge?######",
    "rechargeWYUrl":"http://wxapp.dahengdian.com/mobile/h5/userRechargeWY?######",
    "drawcashUrl":"http://wxapp.dahengdian.com/mobile/h5/userDrawcash?######",
    "userInvitationUrl":"http://www.baidu.com?######",
    "activeUrl":"http://www.baidu.com?######",
    "customerUrl":"http://www.baidu.com?######",
    "aboutUsUrl":"http://www.baidu.com?######",
    "regAgreementUrl":"http://www.baidu.com?######",
    "stat":{
    "userRegCount":200000
    }
    },
    "companyInfo":{
    "name":"浙江博佳投资管理有限公司"
    }
    }
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} system/getConfig 得到系统配置
     * @apiVersion 1.0.2
     * @apiName getConfig
     * @apiDescription 得到系统配置
     * @apiGroup System
     *
     * @apiSuccess {Object} config 系统配置
     * @apiSuccess {String} config.startPageImage 动态启动页图片url（建议拉伸），建议保持缓存12小时
     * @apiSuccess {Number} config.bankBandMax 至多绑定银行卡个数
     * @apiSuccess {String} config.loanAgreementUrl 借款协议url
     * @apiSuccess {String} config.riskBulletinUrl 风险揭示书url
     * @apiSuccess {String} config.rechargeUrl 跳转充值url
     * @apiSuccess {String} config.rechargeUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.rechargeUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.rechargeWYUrl 跳转网银充值url
     * @apiSuccess {String} config.rechargeWYUrl.--money app应用参数，金额
     * @apiSuccess {Number} config.rechargeWYUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.drawcashUrl 跳转提现url
     * @apiSuccess {String} config.drawcashUrl.--money app应用参数，
     * @apiSuccess {String} config.cancelUserUrl 销户（解绑银行卡）url
     *
     * 金额
     * @apiSuccess {Number} config.drawcashUrl.--userBankID app应用参数，用户银行卡ID
     * @apiSuccess {String} config.userInvitationUrl 邀请注册url
     * @apiSuccess {String} config.activeUrl 活动url
     * @apiSuccess {String} config.customerUrl 客服url
     * @apiSuccess {String} config.aboutUsUrl 关于我们url
     * @apiSuccess {String} config.regAgreementUrl 注册协议url
     * @apiSuccess {String} config.stat 统计相关
     * @apiSuccess {String} config.stat.userRegCount 注册用户数
     * @apiSuccess {Object} companyInfo 公司信息
     * @apiSuccess {String} companyInfo.name 公司名称
     * @apiSuccess {String} companyInfo.brand 品牌名称
     * @apiSuccess {String} companyInfo.phone 公司电话
     * @apiSuccess {String} companyInfo.web 公司网址
     * @apiSuccess {String} companyInfo.wechat 公司微信
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "config": {
    "bankBandMax":1,
    "loanAgreementUrl":"http://www.baidu.com?######",
    "riskBulletinUrl":"http://www.baidu.com?######",
    "rechargeUrl":"http://wxapp.dahengdian.com/mobile/h5/userRecharge?######",
    "rechargeWYUrl":"http://wxapp.dahengdian.com/mobile/h5/userRechargeWY?######",
    "drawcashUrl":"http://wxapp.dahengdian.com/mobile/h5/userDrawcash?######",
    "userInvitationUrl":"http://www.baidu.com?######",
    "activeUrl":"http://www.baidu.com?######",
    "customerUrl":"http://www.baidu.com?######",
    "aboutUsUrl":"http://www.baidu.com?######",
    "regAgreementUrl":"http://www.baidu.com?######",
    "stat":{
    "userRegCount":200000
    }
    },
    "companyInfo":{
    "name":"浙江博佳投资管理有限公司",
    "brand":"浙江博佳投资管理有限公司",
    "phone":"浙江博佳投资管理有限公司",
    "web":"浙江博佳投资管理有限公司",
    "wechat":"浙江博佳投资管理有限公司"
    }
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} system/getUpgradeInfoForIOS 得到IOS升级信息
     * @apiVersion 1.0.0
     * @apiName getUpgradeInfoForIOS
     * @apiDescription 得到升级信息
     * @apiGroup System
     *
     * @apiParam {String} versionName 版本号
     * @apiParamExample {json} 发送报文:
    {
    "versionName":'1.1.1'
    }
     *
     * @apiSuccess {String} versionName 版本号
     * @apiSuccess {Array} versionContent 升级内容
     * @apiSuccess {String} download 强制应用商店地址
     * @apiSuccess {Array} channels 决定了那些渠道需要强制更新
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "versionName":"1.23",
    "versionContent":[
        "adsfasdf","131313213","asdfasdfasdfasdasdf"
     ],
    "download":"http://gyxz.exmmw.cn/hk/rj_yx1/aiba.apk",
    "channels":[
        "1123123","123123"
    ]
    }
    }
     * @apiUse CreateUserError
     */

    /**
     * @api {post} system/getUpgradeInfoForIOS 得到升级信息
     * @apiVersion 1.0.0
     * @apiName getUpgradeInfo
     * @apiDescription 得到升级信息
     * @apiGroup System
     *
     * @apiParam {String} versionName 版本号
     * @apiParamExample {json} 发送报文:
    {
    "versionName":'1.1.1'
    }
     *
     * @apiSuccess {String} versionName 版本号
     * @apiSuccess {Number} isForce 是否强制升级，0-不强制升级，1-强制升级
     * @apiSuccess {String} downloadAndroid Android下载地址
     * @apiSuccess {String} downloadIOS IOS下载地址
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "versionName":"1.23",
    "isForce":1,
    "downloadAndroid":"http://gyxz.exmmw.cn/hk/rj_yx1/aiba.apk",
    "downloadIOS":"http://gyxz.exmmw.cn/hk/rj_yx1/aiba.apk",
    "day":10
    }
    }
     * @apiUse CreateUserError
     */
