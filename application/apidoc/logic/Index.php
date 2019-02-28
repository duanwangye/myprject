<?php

    /**
     * @api {post} index/getIndexInfo 得到首页展示信息
     * @apiVersion 1.0.0
     * @apiName getIndexInfo
     * @apiDescription 得到首页展示信息
     * @apiGroup Index
     *
     * @apiSuccess {String} notice1 banner下左边文字
     * @apiSuccess {String} notice2 banner下右边文字
     * @apiSuccess {Object[]} buttonBigList 首页大button配置信息
     * @apiSuccess {String} buttonBigList.imageUrl button图片
     * @apiSuccess {String} buttonBigList.link 点击跳转链接H5，如果为空则原生
     * @apiSuccess {String} buttonBigList.text 描述
     * @apiSuccess {String} buttonBigList.subText 二级描述
     * @apiSuccess {Object[]} bannerList banner
     * @apiSuccess {String} bannerList.title banner描述
     * @apiSuccess {String} bannerList.thumb 图片链接
     * @apiSuccess {String} bannerList.link 点击跳转
     * @apiSuccess {Object} subjectHot 热门产品（只一条），包含参数参考得到产品详细
     * @apiSuccess {Object[]} subjectRecommendList 推荐茶品（多条），包含参数参考得到产品列表
     * @apiSuccess {String} bottomIcon 首页底部图标
     * @apiSuccess {Object} stat 累计数据
     * @apiSuccess {String} stat.tradeTotal 累计交易额
     * @apiSuccess {String} stat.userMakeTotal 为用户赚钱总金额
     * @apiSuccess {String} stat.safeDayTotal 安全运营
     * @apiSuccess {String} stat.userTotal 累计用户
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "notice1":"12312312312312312321",
    "notice2":"12312312312312312321",
    "buttonBigList": [
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/OOH3JloaaV.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": ""
    },
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/HFrBVdHVN9.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": ""
    },
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/REP5LEB1Np.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": ""
    }
    ],
    "bannerList": [
    {
    "title": "pcpcpcpcppc",
    "thumb": "http://slb.dahengdian.com/51zhangxi/2017/12/22/SlJW6XJGKl.jpg",
    "link": "asdfasdfadasdfasdf",
    "subText": ""
    }
    ],
    "subjectHot": {
    "subjectID": 17,
    "term": 30,
    "title": "多融宝31115期",
    "price": "70000.00",
    "year": "10.50",
    "basePrice": "100.00",
    "baseMaxPrice": "70000.00",
    "addTime": "2017-12-01 21:14:33",
    "status": 3,
    "alias": "20171201211433vxPG7p",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-31",
    "interestBeginTime": "购买日",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "满标计息",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": 6.8645833333333,
    "repayTime": "2017-12-31",
    "reachTime": "2018-01-01",
    "unit": "天"
    },
    "subjectRecommendList": [
    {
    "subjectID": 20,
    "term": 15,
    "title": "新手标1212期",
    "price": "105000.00",
    "year": "18.00",
    "basePrice": "100.00",
    "baseMaxPrice": "105000.00",
    "addTime": "2017-12-03 19:34:07",
    "status": 3,
    "alias": "20171203193407V5e9Fu",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-18",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "满标计息",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": -6.1881944444444,
    "repayTime": "2017-12-18",
    "reachTime": "2017-12-19",
    "unit": "天"
    },
    {
    "subjectID": 22,
    "term": 1,
    "title": "新手理财81899",
    "price": "100000.00",
    "year": "12.80",
    "basePrice": "100.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-04 11:12:47",
    "status": 3,
    "alias": "20171204111247GuSFSa",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-05",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 3,
    "name": "T成交日 + 0",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "3000.00",
    "timesInvest": 3
    },
    "statusText": "抢购中",
    "investDay": -20,
    "repayTime": "2017-12-05",
    "reachTime": "2017-12-06",
    "unit": "天"
    },
    {
    "subjectID": 38,
    "term": 15,
    "title": "老手标2233",
    "price": "1000000.00",
    "year": "15.30",
    "basePrice": "100.00",
    "baseMaxPrice": "1000000.00",
    "addTime": "2017-12-21 14:43:21",
    "status": 3,
    "alias": "20171221144321HtfKhG",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2018-01-05",
    "interestBeginTime": "购买日",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 3,
    "name": "T成交日 + 0",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": 11,
    "repayTime": "2018-01-05",
    "reachTime": "2018-01-06",
    "unit": "天"
    }
    ],
    "bottomIcon":"http://www.baidu.com/sdfsdfsd.jpg",
    "stat":{
    "tradeTotal":"120000.03",
    "userMakeTotal":"12000.00",
    "safeDayTotal":"1232",
    "userTotal":"12313362"
    }
    }
    }
     * @apiUse CreateUserError
     */
    /**
     * @api {post} index/getIndexInfo 得到首页展示信息
     * @apiVersion 1.0.1
     * @apiName getIndexInfo
     * @apiDescription 得到首页展示信息
     * @apiGroup Index
     *
     * @apiSuccess {String} notice1 banner下左边文字，如果notice1和notice2同时为空字符串，则不显示该行（UI注意哦）
     * @apiSuccess {String} notice2 banner下右边文字，如果notice1和notice2同时为空字符串，则不显示该行（UI注意哦）
     * @apiSuccess {Array} noticeList 滚动信息
     * @apiSuccess {Object} noticeListConfig 滚动信息配置
     * @apiSuccess {Number} noticeListConfig.cycle 滚动周期，单位毫秒
     * @apiSuccess {Object[]} buttonBigList 首页大button配置信息
     * @apiSuccess {String} buttonBigList.imageUrl button图片
     * @apiSuccess {String} buttonBigList.link 点击跳转链接H5，如果为空则原生
     * @apiSuccess {String} buttonBigList.text 描述
     * @apiSuccess {String} buttonBigList.subText 二级描述
     * @apiSuccess {Object} buttonBigList.share 分享参数，如果没有分享功能，则该参数不返回
     * @apiSuccess {String} buttonBigList.share.headImgUrl 分享图标
     * @apiSuccess {String} buttonBigList.share.title 分享标题
     * @apiSuccess {String} buttonBigList.share.desc 分享描述
     * @apiSuccess {String} buttonBigList.share.link 分享链接
     * @apiSuccess {Object[]} bannerList banner
     * @apiSuccess {String} bannerList.title banner描述
     * @apiSuccess {String} bannerList.thumb 图片链接
     * @apiSuccess {String} bannerList.link 点击跳转
     * @apiSuccess {Object} subjectHot 热门产品（只一条），包含参数参考得到产品详细
     * @apiSuccess {Object[]} subjectRecommendList 推荐茶品（多条），包含参数参考得到产品列表
     * @apiSuccess {String} bottomIcon 首页底部图标
     * @apiSuccess {Object} stat 累计数据
     * @apiSuccess {String} stat.tradeTotal 累计交易额
     * @apiSuccess {String} stat.userMakeTotal 为用户赚钱总金额
     * @apiSuccess {String} stat.safeDayTotal 安全运营
     * @apiSuccess {String} stat.userTotal 累计用户
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "notice1":"12312312312312312321",
    "notice2":"12312312312312312321",
    "noticeList":{
    "config":{
    "cycle":1000
    },
    "list": ["1312****728投资了新手标1月25期 10000.00元","1317****708投资了新手标1月26期 1000.00元","1373****762 刚刚开通新账号","1598****479完成了一笔1888.00元的充值","1732****500投资了新手标1月26期 10000.00元","1306****169 刚刚开通新账号","1368****197投资了新手标1月25期 1000.00元","1860****571 刚刚开通新账号","1523****137投资了享车贷1月第30期 100.00元","1306****169完成了一笔1000.00元的充值","1731****863完成了一笔1000.00元的充值","1306****169投资了新手标3月第25期 100.00元","1598****479完成了一笔2000.00元的充值","1554****067投资了新手标1月27期 5000.00元","1833****165投资了新手标1月28期 10000.00元","1598****479完成了一笔2000.00元的充值","1598****479完成了一笔1500.00元的充值","1313****523 刚刚开通新账号","1598****479完成了一笔1000.00元的充值","1598****479投资了新手标1月第29期 2000.00元","1306****169完成了一笔100.00元的充值","1804****776投资了新手标1月25期 15000.00元","1598****479 刚刚开通新账号","1393****736投资了新手标1月25期 5000.00元","1598****479完成了一笔1000.00元的充值","1731****863投资了享车贷3月第13期 1000.00元","1598****479完成了一笔100.00元的充值","1598****479完成了一笔999999.00元的充值","1523****137完成了一笔100000.00元的充值","1304****676投资了新手标1月25期 10000.00元","1306****169完成了一笔100.00元的充值","1596****705 刚刚开通新账号","1598****479完成了一笔3000.00元的充值","1863****972投资了新手标1月27期 4000.00元","1523****137完成了一笔1.00元的充值","1837****677投资了新手标1月25期 5000.00元","1810****660投资了新手标1月26期 10000.00元","1595****435投资了新手标1月25期 10000.00元","1731****863 刚刚开通新账号","1348****417投资了新手标1月26期 100.00元","1598****479完成了一笔100.00元的充值","1598****479完成了一笔100000.00元的充值","1802****297投资了新手标1月28期 8500.00元","1351****371 刚刚开通新账号","1805****155 刚刚开通新账号","1598****479完成了一笔100.00元的充值","1731****863完成了一笔100000.00元的充值","1351****371投资了新手标1月第29期 2600.00元","1598****479完成了一笔500.00元的充值","1733****529 刚刚开通新账号"]
    },
    "buttonBigList": [
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/OOH3JloaaV.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": "",
    "share": {
    "headImgUrl":"https://static.qimai.cn/static/img/newaso100@2x.png",
    "title":"11334466778899",
    "desc":"11334466778899",
    "link":"http://www.qq.com"
    }
    },
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/HFrBVdHVN9.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": ""
    },
    {
    "imageUrl": "http://slb.dahengdian.com/jiaqiancaifu/2017/12/25/REP5LEB1Np.png",
    "link": "http://www.baidu.com",
    "text": "",
    "subText": ""
    }
    ],
    "bannerList": [
    {
    "title": "pcpcpcpcppc",
    "thumb": "http://slb.dahengdian.com/51zhangxi/2017/12/22/SlJW6XJGKl.jpg",
    "link": "asdfasdfadasdfasdf",
    "subText": ""
    }
    ],
    "subjectHot": {
    "subjectID": 17,
    "term": 30,
    "title": "多融宝31115期",
    "price": "70000.00",
    "year": "10.50",
    "basePrice": "100.00",
    "baseMaxPrice": "70000.00",
    "addTime": "2017-12-01 21:14:33",
    "status": 3,
    "alias": "20171201211433vxPG7p",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-31",
    "interestBeginTime": "购买日",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "满标计息",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": 6.8645833333333,
    "repayTime": "2017-12-31",
    "reachTime": "2018-01-01",
    "unit": "天"
    },
    "subjectRecommendList": [
    {
    "subjectID": 20,
    "term": 15,
    "title": "新手标1212期",
    "price": "105000.00",
    "year": "18.00",
    "basePrice": "100.00",
    "baseMaxPrice": "105000.00",
    "addTime": "2017-12-03 19:34:07",
    "status": 3,
    "alias": "20171203193407V5e9Fu",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-18",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 1,
    "name": "满标计息",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": -6.1881944444444,
    "repayTime": "2017-12-18",
    "reachTime": "2017-12-19",
    "unit": "天"
    },
    {
    "subjectID": 22,
    "term": 1,
    "title": "新手理财81899",
    "price": "100000.00",
    "year": "12.80",
    "basePrice": "100.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-04 11:12:47",
    "status": 3,
    "alias": "20171204111247GuSFSa",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-05",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 3,
    "name": "T成交日 + 0",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "3000.00",
    "timesInvest": 3
    },
    "statusText": "抢购中",
    "investDay": -20,
    "repayTime": "2017-12-05",
    "reachTime": "2017-12-06",
    "unit": "天"
    },
    {
    "subjectID": 38,
    "term": 15,
    "title": "老手标2233",
    "price": "1000000.00",
    "year": "15.30",
    "basePrice": "100.00",
    "baseMaxPrice": "1000000.00",
    "addTime": "2017-12-21 14:43:21",
    "status": 3,
    "alias": "20171221144321HtfKhG",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2018-01-05",
    "interestBeginTime": "购买日",
    "fullTime": 0,
    "statusLoan": 1,
    "subjectType": {
    "subjectTypeID": 1,
    "name": "新手理财",
    "icon": "http://www.baidu.com/jpg.jpg"
    },
    "interestType": {
    "interestTypeID": 1,
    "name": "一次性还本付息"
    },
    "interestTimeType": {
    "interestTimeTypeID": 3,
    "name": "T成交日 + 0",
    "text":"年化利率"
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "抢购中",
    "investDay": 11,
    "repayTime": "2018-01-05",
    "reachTime": "2018-01-06",
    "unit": "天"
    }
    ],
    "bottomIcon":"http://www.baidu.com/sdfsdfsd.jpg",
    "stat":{
    "tradeTotal":"120000.03",
    "userMakeTotal":"12000.00",
    "safeDayTotal":"1232",
    "userTotal":"12313362"
    }
    }
    }
     * @apiUse CreateUserError
     */

