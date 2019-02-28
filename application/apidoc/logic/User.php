<?php


    /**
     * @api {post} user/checkUserByMobile 检测是否为新用户
     * @apiVersion 1.0.0
     * @apiName checkUserByMobile
     * @apiDescription 检测是否为新用户
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     *
     *
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523"
    }
     * @apiSuccess {Number} status 为0，新用户；为1已注册
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content":{
    "status":0
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/registerSendMobileCode 注册新用户发送验证码
     * @apiVersion 1.0.0
     * @apiName registerSendMobileCode
     * @apiDescription 注册新用户发送验证码，仅限国内手机号
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/register 注册新用户
     * @apiVersion 1.0.0
     * @apiName register
     * @apiDescription 注册新用户
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} mobileCode 手机号码，6位数字
     * @apiParam {String} password 密码
     * @apiParam {String} passwordRe 重复密码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523",
    "mobileCode": "2145",
    "password": "12313132",
    "passwordRe": "66332221144"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content":{
    "token":"a8ajdsjasdfuufayg8aasdfasdfasd"
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/loginByPassword 密码登录
     * @apiVersion 1.0.0
     * @apiName loginByPassword
     * @apiDescription 注册新用户
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} password 密码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523",
    "password": "111111"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "token": "a8ajdsjasdfuufayg8aasdfasdfasd",
    "userInfo":{
    "uuid":"123123123123123123"
    }
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/loginSendMobileCode 登录/重置密码发送验证码
     * @apiVersion 1.0.0
     * @apiName loginSendMobileCode
     * @apiDescription 登录/重置密码发送验证码
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/loginByMobileCode 短信验证登录
     * @apiVersion 1.0.0
     * @apiName loginByMobileCode
     * @apiDescription 短信验证登录
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} mobileCode 验证码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523",
    "mobileCode": "111111"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "token": "a8ajdsjasdfuufayg8aasdfasdfasd",
    "userInfo":{
    "uuid":"123123123123123123"
    }
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/logout 退出登录
     * @apiVersion 1.0.0
     * @apiName logout
     * @apiDescription 退出登录，退出登录后返回首页
     * @apiGroup User
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/resetPassword 重置密码
     * @apiVersion 1.0.0
     * @apiName resetPassword
     * @apiDescription 重置密码，重置之后要重新登录
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} mobileCode 验证码
     * @apiParam {String} password 密码
     * @apiParam {String} passwordRe 重复密码
     * @apiParamExample {json} 发送报文:
    {
    "mobile": "13136180523",
    "mobileCode": "111111",
    "password":"123123123123123123",
    "passwordRe":"123123123123123123"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/recharge 充值
     * @apiVersion 1.0.0
     * @apiName recharge
     * @apiDescription 充值
     * @apiGroup User
     *
     * @apiParam {Number} userBankID 银行卡ID
     * @apiParam {Decimal} money 金额
     * @apiParamExample {json} 发送报文:
    {
    "app": {
    "userBankID": 12,
    "money": 12.01
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     *
     * @apiSuccess {Number} userRechargeID 充值ID
     * @apiSuccess {String} alias 充值单号
     * @apiSuccess {String} outerAlias 第三方支付订单号
     * @apiSuccess {String} notify 支付完成第三方异步回调（通知服务器）
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "userRechargeID": 12,
    "alias": "DC12981726638899",
    "outerAlias": "12981726638899",
    "notify": "http://wxapp.dahengdian.com/mobile/notify/index"
    },
    "sign":"4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/checkInfo 添加银行卡和实名统一接口
     * @apiVersion 1.0.0
     * @apiName checkInfo
     * @apiDescription 添加银行卡和实名统一接口，验证通过后自动添加一个银行卡；该接口大概有2秒左右的延时，请界面做好优化
     * @apiGroup User
     *
     * @apiParam {Number} bankID 银行ID
     * @apiParam {String} trueName 真实姓名
     * @apiParam {String} passport 身份证号码
     * @apiParam {String} bankNumber 银行卡号码
     * @apiParam {String} mobile 手机号码
     * @apiParam {String} mobileCode 手机验证码
     * @apiParam {String} [payword] 支付密码，密码为6位纯数字
     * @apiParam {String} [paywordRe] 重复支付密码，密码为6位纯数字
     * @apiParamExample {json} 发送报文:
    {
    "bankID": 12,
    "trueName": "罗贯中",
    "passport": "230354635879109",
    "bankNumber": "63309871627382991010",
    "mobileCode": "123456",
    "mobile":"13136180523",
    "payword":"123456",
    "paywordRe":"123456"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} user/checkInfo 添加银行卡和实名统一接口
     * @apiVersion 1.0.1
     * @apiName checkInfo
     * @apiDescription 添加银行卡和实名统一接口，验证通过后自动添加一个银行卡；该接口大概有1秒左右的延时，请界面做好优化；app版本号2.1.2注意手机号隐藏和取消了手机短信验证
     * @apiGroup User
     *
     * @apiParam {Number} bankID 银行ID
     * @apiParam {String} trueName 真实姓名
     * @apiParam {String} passport 身份证号码
     * @apiParam {String} bankNumber 银行卡号码
     * @apiParam {String} mobile 手机号码，手机号码从app版本号2.1.2后UI隐藏，传值固定为注册手机号
     * @apiParam {String} mobileCode 手机验证码，手机验证码从app版本号2.1.2后UI隐藏，传值固定为687888
     * @apiParam {String} payword 支付密码，密码至少为6位数字和英文字母
     * @apiParam {String} paywordRe 重复支付密码，密码至少为6位数字和英文字母
     * @apiParamExample {json} 发送报文:
    {
    "bankID": 12,
    "trueName": "罗贯中",
    "passport": "230354635879109",
    "bankNumber": "63309871627382991010",
    "mobileCode": "123456",
    "mobile":"13136180523",
    "payword":"123456",
    "paywordRe":"123456"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/checkInfoSendMobileCode 预留手机发送验证码
     * @apiVersion 1.0.0
     * @apiName checkInfoSendMobileCode
     * @apiDescription 添加银行卡之前发送验证码，该手机号码为银行卡预留手机号
     * @apiGroup User
     *
     * @apiParam {String} mobile 手机号码
     * @apiParamExample {json} 发送报文:
    {
    "app": {
    "mobile": "13136180523"
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     *
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "sign":"4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/getUserBankList 得到我的银行卡列表
     * @apiVersion 1.0.0
     * @apiName getUserBankList
     * @apiDescription 得到我的银行卡列表
     * @apiGroup User
     *
     *
     *
     * @apiSuccess {Object[]} userBankList 银行卡列表
     * @apiSuccess {Number} userBankList.userBankID 该银行卡ID
     * @apiSuccess {String} userBankList.bankAccount 姓名
     * @apiSuccess {String} userBankList.bankNumberAsterisk 银行卡号码，加密
     * @apiSuccess {String} userBankList.bankNameFull 银行全称，带支行
     * @apiSuccess {String} userBankList.mobile 预留手机号
     * @apiSuccess {String} userBankList.isDefault 1默认，0非默认
     * @apiSuccess {Object} userBankList.bank 银行
     * @apiSuccess {String} userBankList.bank.bankName 银行名称
     * @apiSuccess {String} userBankList.bank.appIcon 银行app图标
     * @apiSuccess {String} userBankList.bank.color 银行颜色（废弃）
     * @apiSuccess {String} userBankList.bank.backgroundColor 银行背景色
     * @apiSuccess {String} userBankList.bank.quotaTitle 限额名称描述
     * @apiSuccess {String} userBankList.bank.quotaSingleOrder 单笔限额，单位元
     * @apiSuccess {String} userBankList.bank.quotaSingleDay 单日限额
     * @apiSuccess {String} userBankList.bank.quotaSingleMouth 单月限额
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "userBankList": [
    {
    "userBankID": 5,
    "bankAccount": "张奇",
    "bankNameFull": "",
    "mobile": "17316900863",
    "isDefault": 1,
    "bank": {
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
    },
    "bankNumberAsterisk": "6217 **** **** **** 6968"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/getUserHongbaoList 得到我的红包列表
     * @apiVersion 1.0.0
     * @apiName getUserHongbaoList
     * @apiDescription 得到我的红包列表
     * @apiGroup User
     *
     * @apiParam {Number=0,1,2,3} [status] 红包状态，0为所有，1-未使用，2-已使用，3-已过期
     * @apiParamExample {json} 发送报文:
    {
    "status": 1
    }
     *
     * @apiSuccess {Object[]} userHongbaoList 红包列表列表
     * @apiSuccess {Number} userHongbaoList.userHongbaoID 红包ID
     * @apiSuccess {Object} userHongbaoList.hongbao 红包
     * @apiSuccess {String} userHongbaoList.hongbao.title 红包名称
     * @apiSuccess {String} userHongbaoList.hongbao.money 红包金额，现金券时有效
     * @apiSuccess {String} userHongbaoList.hongbao.year 加息年化，加息券有效
     * @apiSuccess {Number} userHongbaoList.hongbao.typeID 1-现金券，2-加息券
     * @apiSuccess {String} userHongbaoList.hongbao.minMoney 投资满多少可使用
     * @apiSuccess {Number} userHongbaoList.hongbao.minDay 不低于多少天的产品可以使用，比如该值为30时，那么低于30天产品可以使用
     * @apiSuccess {String} userHongbaoList.hongbao.minMoneyText minMoney描述
     * @apiSuccess {String} userHongbaoList.hongbao.minDayText minDay描述
     * @apiSuccess {String} userHongbaoList.hongbao.buyText 购买时描述
     * @apiSuccess {String} userHongbaoList.beginTime 有效开始时间
     * @apiSuccess {String} userHongbaoList.endTime 有效结束时间
     * @apiSuccess {Number} userHongbaoList.status 1-未使用，2-已使用，3-已过期
     * @apiSuccess {String} userHongbaoList.addTime 添加时间
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "hongbaoList": [
    {
    "userHongbaoID": 2,
    "beginTime": "2017-12-24",
    "endTime": "2018-01-08",
    "addTime": "2017-12-24 22:30:31",
    "status": 1,
    "hongbao": {
    "title": "新手注册",
    "typeID": 1,
    "minDay": 0,
    "minMoney": "0.00",
    "year": "0.00",
    "money": "100.00",
    "minMoneyText": "满0元使用",
    "minDayText": "所有产品通用",
    "buyText":"现金券88元"
    }
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/getUserInfo 得到个人信息唯一接口
     * @apiVersion 1.0.0
     * @apiName getUserInfo
     * @apiDescription 得到个人信息唯一接口
     * @apiGroup User
     *
     * @apiSuccess {Object} userInfo 红包列表列表
     * @apiSuccess {Number} userInfo.userID 红包ID
     * @apiSuccess {Object} userInfo.mobile 手机号，该手机号只做展示用
     * @apiSuccess {String} userInfo.passport 身份证
     * @apiSuccess {String} userInfo.trueName 真实姓名
     * @apiSuccess {String} userInfo.avatar 头像
     * @apiSuccess {String} userInfo.gender 性别，0-不男不女，1-男，2-女
     * @apiSuccess {String} userInfo.loginTime 最后一次登录时间
     * @apiSuccess {Number=0,1} userInfo.isAuthTrueName 是否实名认证，0否
     * @apiSuccess {Number=0,1} userInfo.isAuthBank 是否绑定银行，目前用于判断实名认证和银行卡认证，0否
     * @apiSuccess {Number=0,1} userInfo.isNewInvest 是否是首投用户，0否
     * @apiSuccess {Object} userInfo.userAccount 个人资金账户概况
     * @apiSuccess {String} userInfo.userAccount.money 可用余额
     * @apiSuccess {String} userInfo.userAccount.moneyTotal 资产总额
     * @apiSuccess {String} userInfo.userAccount.moneyAcc 累积收益
     * @apiSuccess {String} userInfo.userAccount.moneyYesterday 昨日收益
     * @apiSuccess {String} userInfo.userAccount.moneyToday 今日收益
     * @apiSuccess {String} userInfo.userAccount.waitBen 待收总本金
     * @apiSuccess {String} userInfo.userAccount.waitInterest 待收总利息
     * @apiSuccess {String} userInfo.userAccount.hasInvestBenTotal 共已投入本金
     * @apiSuccess {String} userInfo.userAccount.hasInvestMoneyTotal 共已投入实际金额
     * @apiSuccess {String} userInfo.userAccount.hasRepayBenTotal 共已收回的本金
     * @apiSuccess {String} userInfo.userAccount.hasRepayInterestTotal 共已收回的利息
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "userInfo": {
    "userID": 8,
    "mobile": "17316900863",
    "trueName": "张奇",
    "passport": "230253654125663547",
    "avatar": "",
    "gender": 0,
    "loginTime": 0,
    "isAuthTrueName": 0,
    "isAuthBank": 0,
    "isNewInvest": 0,
    "userAccount": {
    "moneyTotal": "0.00",
    "money": "0.00",
    "moneyAcc": "0.00",
    "moneyYesterday": 0,
    "moneyToday": "0.00",
    "waitBen": 0,
    "waitInterest": "0.00",
    "hasInvestBenTotal": 0,
    "hasInvestMoneyTotal": "0.00",
    "hasRepayBenTotal": "0.00",
    "hasRepayInterestTotal":""
    }
    }
    }
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} user/getUserInfo 得到个人信息唯一接口
     * @apiVersion 1.0.1
     * @apiName getUserInfo
     * @apiDescription 得到个人信息唯一接口
     * @apiGroup User
     *
     * @apiSuccess {Object} userInfo 红包列表列表
     * @apiSuccess {Number} userInfo.userID 红包ID
     * @apiSuccess {Object} userInfo.mobile 手机号，该手机号只做展示用
     * @apiSuccess {String} userInfo.passport 身份证
     * @apiSuccess {String} userInfo.trueName 真实姓名
     * @apiSuccess {String} userInfo.avatar 头像
     * @apiSuccess {String} userInfo.gender 性别，0-不男不女，1-男，2-女
     * @apiSuccess {String} userInfo.loginTime 最后一次登录时间
     * @apiSuccess {Number=0,1} userInfo.isAuthTrueName 是否实名认证，0否
     * @apiSuccess {Number=0,1} userInfo.isAuthBank 是否绑定银行，目前用于判断实名认证和银行卡认证，0否
     * @apiSuccess {Number=0,1} userInfo.isNewInvest 是否是首投用户，0否
     * @apiSuccess {Object} userInfo.userAccount 个人资金账户概况
     * @apiSuccess {String} userInfo.userAccount.money 可用余额
     * @apiSuccess {String} userInfo.userAccount.moneyTotal 资产总额
     * @apiSuccess {String} userInfo.userAccount.moneyAcc 累积收益
     * @apiSuccess {String} userInfo.userAccount.moneyYesterday 昨日收益
     * @apiSuccess {String} userInfo.userAccount.moneyToday 今日收益
     * @apiSuccess {String} userInfo.userAccount.waitBen 待收总本金
     * @apiSuccess {String} userInfo.userAccount.waitInterest 待收总利息
     * @apiSuccess {String} userInfo.userAccount.hasInvestBenTotal 共已投入本金
     * @apiSuccess {String} userInfo.userAccount.hasInvestMoneyTotal 共已投入实际金额
     * @apiSuccess {String} userInfo.userAccount.hasRepayBenTotal 共已收回的本金
     * @apiSuccess {String} userInfo.userAccount.hasRepayInterestTotal 共已收回的利息
     * @apiSuccess {Object} userInfo.bank 默认银行卡，如果客户未绑定银行卡，则不显示该字段
     * @apiSuccess {Number} userInfo.bank.userBankID 银行卡ID
     * @apiSuccess {String} userInfo.bank.bankNumber 银行卡号码
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "userInfo": {
    "userID": 8,
    "mobile": "17316900863",
    "trueName": "张奇",
    "passport": "230253654125663547",
    "avatar": "",
    "gender": 0,
    "loginTime": 0,
    "isAuthTrueName": 0,
    "isAuthBank": 0,
    "isNewInvest": 0,
    "userAccount": {
    "moneyTotal": "0.00",
    "money": "0.00",
    "moneyAcc": "0.00",
    "moneyYesterday": 0,
    "moneyToday": "0.00",
    "waitBen": 0,
    "waitInterest": "0.00",
    "hasInvestBenTotal": 0,
    "hasInvestMoneyTotal": "0.00",
    "hasRepayBenTotal": "0.00",
    "hasRepayInterestTotal":""
    },
    "bank": {
    "userBankID": 123,
    "bankNumber": "62236541254796441"
    }
    }
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} user/getUserFinanceList 得到交易流水
     * @apiVersion 1.0.0
     * @apiName getUserFinanceList
     * @apiDescription 得到交易流水
     * @apiGroup User
     *
     * @apiParam {String=[0,1,2,3,4,5]} mode 模块 0-所有，1-充值，2-提现，3-下单，4-回款，5-其他
     * @apiParam {Number} [pageIndex] 页码
     * @apiParam {Number} [pageItemCount] 每页条数
     * @apiParamExample {json} 发送报文:
    {
    "mode": 1,
    "pageIndex":1,
    "pageItemCount":0
    }
     *
     * @apiSuccess {String} mode 模块标识，MODE_RECHARGE-充值，MODE_DRAWCASH-提现，MODE_CANG-购买，MODE_REPAY_INTEREST-利息回款，MODE_REPAY_BEN-本金回款，MODE_EXT-其他
     * @apiSuccess {Number} modeID 模块对应的条目ID
     * @apiSuccess {String} modeText 模块描述（状态描述）
     * @apiSuccess {String} addTime 添加时间
     * @apiSuccess {String} updateTime 更新时间
     * @apiSuccess {Number} userID 用户ID
     * @apiSuccess {String} moneyPre 交易之前可用余额
     * @apiSuccess {String} money 交易金额
     * @apiSuccess {String} moneyNow 交易之后可用余额
     * @apiSuccess {Number} status 状态
     * @apiSuccess {Number} userFinanceID 流水ID
     * @apiSuccess {Number} pageIndex 页码
     * @apiSuccess {Number} pageItemCount 每页条数
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "financeList": [
    {
    "userFinanceID": 5778,
    "mode": "MODE_RECHARGE",
    "modeID": 536,
    "money": "0.10",
    "addTime": "2017-12-30 19:17:38",
    "userID": 8,
    "status": 1,
    "updateTime": "2017-12-30 19:17:38",
    "moneyPre": "0.10",
    "moneyNow": "0.20",
    "modeText": "失败"
    }
    ],
    "count": 1,
    "pageItemCount": 10
    }
    }
     * @apiUse CreateUserError
     */

