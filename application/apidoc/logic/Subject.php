<?php


    /**
     * @api {post} subject/getSubjectList 得到产品列表
     * @apiVersion 1.0.0
     * @apiName getSubjectList
     * @apiDescription 得到产品列表
     * @apiGroup Subject
     *
     * @apiParam {Number=[0,1,2]} [orderTerm] 期限次序，1由高到低，2由低到高，0无效
     * @apiParam {Number=[0,1,2} [orderYear] 年化次序，1由高到低，2由低到高，0无效
     * @apiParam {Number} [term] 期数，比如15，代表15天标
     * @apiParam {Number} [status] 状态，0全部，1热卖中，2已售罄，3还款中
     * @apiParam {Number=[1,2,3]} [subjectTypeID] 1-新手标，2-享车贷，3-优车贷
     * @apiParam {Number} [pageIndex] 页码
     * @apiParam {Number} [pageItemCount] 每页条数
     * @apiParamExample {json} 发送报文:
    {
    "orderTerm": 0,
    "orderYear": 0,
    "term":15,
    "status":15,
    "subjectTypeID":1,
    "pageIndex":1,
    "pageItemCount":0
    }
     *
     * @apiSuccess {Object[]} subjectList 产品列表
     * @apiSuccess {Number} subjectList.subjectID 产品ID
     * @apiSuccess {Number} subjectList.term 期限
     * @apiSuccess {Number} subjectList.unit 期限单位，“天”，“月”，“年”
     * @apiSuccess {String} subjectList.title 标题
     * @apiSuccess {String} subjectList.price 总额
     * @apiSuccess {String} subjectList.year 年化
     * @apiSuccess {String} subjectList.yearSystem 追加年化
     * @apiSuccess {String} subjectList.basePrice 起投金额
     * @apiSuccess {String} subjectList.baseMaxPrice 最大投资金额
     * @apiSuccess {String} subjectList.addTime 添加时间
     * @apiSuccess {String} subjectList.repayTime 还款时间
     * @apiSuccess {Number} subjectList.status 状态值，3-抢购中，4-已售罄
     * @apiSuccess {String} subjectList.statusText 状态描述
     * @apiSuccess {String} subjectList.alias 编号
     * @apiSuccess {Number} subjectList.loanID 借款ID
     * @apiSuccess {Number} subjectList.hongbao 可以使用红包个数
     * @apiSuccess {String} subjectList.interestBeginTime 起息时间
     * @apiSuccess {String} subjectList.overTime 到期时间
     * @apiSuccess {String} subjectList.reachTime 到账时间
     * @apiSuccess {Number} subjectList.investDay 剩余投资天数
     * @apiSuccess {Object} subjectList.subjectType 产品类型
     * @apiSuccess {String} subjectList.subjectType.name 描述
     * @apiSuccess {String} subjectList.subjectType.icon 分类图标
     * @apiSuccess {String} subjectList.subjectType.subjectTypeID 类型ID，1-新手标，2-精品标，3-长久标
     * @apiSuccess {Object} subjectList.interestType 计息类型
     * @apiSuccess {String} subjectList.interestType.name 描述
     * @apiSuccess {Number} subjectList.interestType.interestTypeID 类型ID
     * @apiSuccess {Object} subjectList.interestTimeType 计息时间类型
     * @apiSuccess {String} subjectList.interestTimeType.name 描述
     * @apiSuccess {String} subjectList.interestTimeType.text 年化描述
     * @apiSuccess {Number} subjectList.interestTimeType.interestTimeTypeID 类型ID
     * @apiSuccess {Object} subjectList.subjectStat 统计
     * @apiSuccess {String} subjectList.subjectStat.moneyTotalInvest 已投资
     * @apiSuccess {Number} subjectList.subjectStat.timesInvest 已投次数
     * @apiSuccess {Number} subjectList.contentUrl1 产品说明
     * @apiSuccess {Number} subjectList.contentUrl2 项目详情
     * @apiSuccess {Number} subjectList.contentUrl3 投标记录
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "subjectList": [
    {
    "subjectID": 1,
    "term": 1,
    "unit":"天",
    "title": "新手理财818",
    "price": "100000.00",
    "year": "12.80",
    "yearSystem": "12.80",
    "basePrice": "100.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-04 11:12:47",
    "interestBeginTime":"购买日",
    "reachTime": "2017-12-04",
    "status": 3,
    "alias": "20171204111247GuSFSa",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-05 00:00:00",
    "subjectType": {
    "icon": "http://www.baidu.com/bj.jpg",
    "name": "新手理财",
    "subjectTypeID": 2
    },
    "interestType": {
    "name": "一次性还本付息",
    "interestTypeID": 2
    },
    "interestTimeType": {
    "text": "预期年化",
    "name": "T成交日 + 0",
    "interestTimeTypeID": 2
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "已发布",
    "investDay": 1,
    "repayTime": "2017-12-05 00:00:00",
    "contentUrl1": "http://www.baidu.com",
    "contentUrl2": "http://www.baidu.com",
    "contentUrl3": "http://www.baidu.com"
    }
    ]
    }
    }
     * @apiUse CreateUserError
     */

    /**
     * @api {post} subject/getSubjectDetail 得到产品详细
     * @apiVersion 1.0.0
     * @apiName getSubjectDetail
     * @apiDescription 得到产品详细
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12
    }
     *
     * @apiSuccess {Object} subjectDetail 产品详细
     * @apiSuccess {Number} subjectDetail.subjectID 产品ID
     * @apiSuccess {Number} subjectDetail.term 期限
     * @apiSuccess {Number} subjectDetail.unit 期限单位，“天”，“月”，“年”
     * @apiSuccess {String} subjectDetail.title 标题
     * @apiSuccess {String} subjectDetail.price 总额
     * @apiSuccess {String} subjectDetail.year 年化
     * @apiSuccess {String} subjectDetail.yearSystem 追加年化
     * @apiSuccess {String} subjectDetail.basePrice 起投金额
     * @apiSuccess {String} subjectDetail.baseMaxPrice 最大投资金额
     * @apiSuccess {String} subjectDetail.addTime 添加时间
     * @apiSuccess {String} subjectDetail.repayTime 还款时间
     * @apiSuccess {Number} subjectDetail.status 状态值，3-抢购中，4-已售罄
     * @apiSuccess {String} subjectDetail.statusText 状态描述
     * @apiSuccess {String} subjectDetail.alias 编号
     * @apiSuccess {Number} subjectDetail.loanID 借款ID
     * @apiSuccess {Number} subjectDetail.hongbao 可以使用红包个数
     * @apiSuccess {String} subjectDetail.overTime 到期时间
     * @apiSuccess {String} subjectDetail.interestBeginTime 起息日
     * @apiSuccess {String} subjectDetail.reachTime 到账时间
     * @apiSuccess {Number} subjectDetail.investDay 剩余投资天数
     * @apiSuccess {Object} subjectDetail.subjectType 产品类型
     * @apiSuccess {String} subjectDetail.subjectType.icon 分类图标
     * @apiSuccess {String} subjectDetail.subjectType.name 描述
     * @apiSuccess {String} subjectDetail.subjectType.subjectTypeID 类型ID，1-新手标，2-精品标，3-长久标
     * @apiSuccess {Object} subjectDetail.interestType 计息类型
     * @apiSuccess {String} subjectDetail.interestType.name 描述
     * @apiSuccess {Number} subjectDetail.interestType.interestTypeID 类型ID
     * @apiSuccess {Object} subjectDetail.interestTimeType 计息时间类型
     * @apiSuccess {String} subjectDetail.interestTimeType.name 描述
     * @apiSuccess {String} subjectDetail.interestTimeType.text 年化描述
     * @apiSuccess {Number} subjectDetail.interestTimeType.interestTimeTypeID 类型ID
     * @apiSuccess {Object} subjectDetail.subjectStat 统计
     * @apiSuccess {String} subjectDetail.subjectStat.moneyTotalInvest 已投资
     * @apiSuccess {Number} subjectDetail.subjectStat.timesInvest 已投次数
     * @apiSuccess {String} subjectDetail.contentUrl1 产品说明
     * @apiSuccess {String} subjectDetail.contentUrl2 项目详情
     * @apiSuccess {String} subjectDetail.contentUrl3 投标记录
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "subjectDetail":{
    "subjectID": 1,
    "term": 1,
    "unit":"天",
    "title": "新手理财818",
    "price": "100000.00",
    "year": "12.80",
    "yearSystem": "12.80",
    "basePrice": "100.00",
    "baseMaxPrice": "100000.00",
    "addTime": "2017-12-04 11:12:47",
    "interestBeginTime":"购买日",
    "reachTime": "2017-12-04",
    "status": 3,
    "alias": "20171204111247GuSFSa",
    "loanID": 0,
    "hongbao": 0,
    "overTime": "2017-12-05 00:00:00",
    "subjectType": {
    "icon": "http://www.baidu.com/bj.jpg",
    "name": "新手理财",
    "subjectTypeID": 2
    },
    "interestType": {
    "name": "一次性还本付息",
    "interestTypeID": 2
    },
    "interestTimeType": {
    "text": "预期年化",
    "name": "T成交日 + 0",
    "interestTimeTypeID": 2
    },
    "subjectStat": {
    "moneyTotalInvest": "0.00",
    "timesInvest": 0
    },
    "statusText": "已发布",
    "investDay": 1,
    "repayTime": "2017-12-05 00:00:00"
    },
    "contentUrl1":"http://www.baidu.com",
    "contentUrl2":"http://www.baidu.com",
    "contentUrl3":"http://www.baidu.com"
    }
    }
     * @apiUse CreateUserError
     */


    /**
     * @api {post} subject/getSubjectContent 得到产品合同及投资记录
     * @apiVersion 1.0.0
     * @apiName getSubjectContent
     * @apiDescription 得到产品合同及投资记录
     * @apiGroup Subject
     *
     * @apiParam {Number} subjectID 产品ID
     * @apiParamExample {json} 发送报文:
    {
    "subjectID": 12
    }
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "cangList": [{
    "cangID": 1887,
    "subjectID": 766,
    "userID": 17679,
    "alias": "CP2018021014023800001887",
    "moneySubject": "1000.00",
    "status": 4,
    "addTime": "2018-02-10 14:02:38",
    "payTime": "2018-02-10 15:20:05",
    "updateTime": "2018-02-10 14:02:43",
    "interestBeginTime": "2018-02-10",
    "interestEndTime": "1970-01-02",
    "repayTime": 0,
    "year": "0.00",
    "ben": "1000.00",
    "yearSystem": "0.00",
    "interest": "0.00",
    "investDay": 0,
    "interestTimeTypeID": 0,
    "isForged": 1,
    "yearExt": "0.00",
    "hongbao": "",
    "outerNumber": "",
    "osType": 0,
    "ip": "",
    "money": "0.00",
    "BACKUPID": 0,
    "masterID": "",
    "isFutou": 0,
    "user": {
    "userID": 17679,
    "uuid": "",
    "trueName": "李平平",
    "mobile": "17355246589",
    "password": "asdfasdfasasdfasdfasdfasdfasdfsd",
    "channelID": 0,
    "passport": "",
    "addTime": "1970-01-01 08:00:00",
    "updateTime": "1970-01-01 08:00:00",
    "status": 0,
    "token": "",
    "tokenOverTime": 0,
    "isAuthTrueName": 0,
    "isAuthBank": 0,
    "isFuyougold": 0,
    "avatar": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/01\/03\/V5KL8LKbLE.png",
    "gender": 0,
    "areaID": 0,
    "deviceID": "",
    "osV": "",
    "osType": 0,
    "ip": "",
    "isForged": 1,
    "isNewInvest": 0,
    "tokenWap": "",
    "tokenPc": "",
    "tokenOverTimeWap": 0,
    "tokenOverTimePc": 0,
    "loginTime": "",
    "loginOsType": 0,
    "vip": 0,
    "email": "",
    "BACKUPID": 0,
    "apiV": "",
    "appV": ""
    }
    }, {
    "cangID": 1880,
    "subjectID": 766,
    "userID": 19994,
    "alias": "CP2018021011443200001880",
    "moneySubject": "4600.00",
    "status": 3,
    "addTime": "2018-02-10 11:44:32",
    "payTime": "2018-02-10 11:44:32",
    "updateTime": "2018-02-10 11:44:32",
    "interestBeginTime": "2018-02-10",
    "interestEndTime": "2018-02-25",
    "repayTime": 0,
    "year": "18.00",
    "ben": "4600.00",
    "yearSystem": "0.00",
    "interest": "36.30",
    "investDay": 15,
    "interestTimeTypeID": 3,
    "isForged": 0,
    "yearExt": "1.20",
    "hongbao": "423820",
    "outerNumber": "fuyou151823427212578",
    "osType": 1,
    "ip": "218.57.182.73",
    "money": "4600.00",
    "BACKUPID": 0,
    "masterID": "",
    "isFutou": 0,
    "user": {
    "userID": 19994,
    "uuid": "5Ceh4akjW3EB0tMikG3LG8xCeeS0ONUS",
    "trueName": "王敏",
    "mobile": "13792629520",
    "password": "d161a617d5e174e450600ad6bd91cdee",
    "channelID": 68,
    "passport": "370724198812061861",
    "addTime": "2018-02-10 11:40:04",
    "updateTime": "2018-02-10 12:12:17",
    "status": 0,
    "token": "",
    "tokenOverTime": 0,
    "isAuthTrueName": 1,
    "isAuthBank": 1,
    "isFuyougold": 0,
    "avatar": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/01\/03\/V5KL8LKbLE.png",
    "gender": 0,
    "areaID": 0,
    "deviceID": "A812975A-901A-45DB-AFEE-1902D04F4FF4",
    "osV": "10.3.3",
    "osType": 1,
    "ip": "218.57.182.73",
    "isForged": 0,
    "isNewInvest": 0,
    "tokenWap": "",
    "tokenPc": "",
    "tokenOverTimeWap": 0,
    "tokenOverTimePc": 0,
    "loginTime": 0,
    "loginOsType": 1,
    "vip": 0,
    "email": "",
    "BACKUPID": 0,
    "apiV": "",
    "appV": "1.0"
    }
    }, {
    "cangID": 1876,
    "subjectID": 766,
    "userID": 19993,
    "alias": "CP2018021011152700001876",
    "moneySubject": "1000.00",
    "status": 3,
    "addTime": "2018-02-10 11:15:27",
    "payTime": "2018-02-10 11:15:27",
    "updateTime": "2018-02-10 11:15:32",
    "interestBeginTime": "2018-02-10",
    "interestEndTime": "2018-02-25",
    "repayTime": 0,
    "year": "18.00",
    "ben": "1000.00",
    "yearSystem": "0.00",
    "interest": "7.89",
    "investDay": 15,
    "interestTimeTypeID": 3,
    "isForged": 0,
    "yearExt": "1.20",
    "hongbao": "423789",
    "outerNumber": "fuyou151823252788142",
    "osType": 2,
    "ip": "192.168.2.200",
    "money": "1000.00",
    "BACKUPID": 0,
    "masterID": "",
    "isFutou": 0,
    "user": {
    "userID": 19993,
    "uuid": "MKQunLSM7X9WG5UaEn6rzhAeH5OTwbkk",
    "trueName": "周海",
    "mobile": "18124214379",
    "password": "396af95650121f7dc1e997f77f0513fb",
    "channelID": 55,
    "passport": "441781199301106811",
    "addTime": "2018-02-10 11:09:49",
    "updateTime": "2018-02-10 11:15:27",
    "status": 0,
    "token": "5cf10e3d81a53d82ab6e9c5b30bc6006",
    "tokenOverTime": 1518836989,
    "isAuthTrueName": 1,
    "isAuthBank": 1,
    "isFuyougold": 0,
    "avatar": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/01\/03\/V5KL8LKbLE.png",
    "gender": 0,
    "areaID": 0,
    "deviceID": "8E4ECC0DD4AA90AACEB64703BA7EEE42",
    "osV": "7.1.1",
    "osType": 2,
    "ip": "192.168.2.200",
    "isForged": 0,
    "isNewInvest": 0,
    "tokenWap": "",
    "tokenPc": "",
    "tokenOverTimeWap": 0,
    "tokenOverTimePc": 0,
    "loginTime": 0,
    "loginOsType": 2,
    "vip": 0,
    "email": "",
    "BACKUPID": 0,
    "apiV": "1.0.0",
    "appV": "2.0.0"
    }
    }, {
    "cangID": 1875,
    "subjectID": 766,
    "userID": 17653,
    "alias": "CP2018021010150200001875",
    "moneySubject": "1000.00",
    "status": 4,
    "addTime": "2018-02-10 10:15:02",
    "payTime": "2018-02-10 15:20:05",
    "updateTime": "2018-02-10 10:15:07",
    "interestBeginTime": "2018-02-10",
    "interestEndTime": "1970-01-02",
    "repayTime": 0,
    "year": "0.00",
    "ben": "1000.00",
    "yearSystem": "0.00",
    "interest": "0.00",
    "investDay": 0,
    "interestTimeTypeID": 0,
    "isForged": 1,
    "yearExt": "0.00",
    "hongbao": "",
    "outerNumber": "",
    "osType": 0,
    "ip": "",
    "money": "0.00",
    "BACKUPID": 0,
    "masterID": "",
    "isFutou": 0,
    "user": {
    "userID": 17653,
    "uuid": "",
    "trueName": "冯伞",
    "mobile": "13715988654",
    "password": "12365478966555555512222222222222",
    "channelID": 0,
    "passport": "",
    "addTime": "1970-01-01 08:00:00",
    "updateTime": "1970-01-01 08:00:00",
    "status": 0,
    "token": "",
    "tokenOverTime": 0,
    "isAuthTrueName": 0,
    "isAuthBank": 0,
    "isFuyougold": 0,
    "avatar": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/01\/03\/V5KL8LKbLE.png",
    "gender": 0,
    "areaID": 0,
    "deviceID": "",
    "osV": "",
    "osType": 0,
    "ip": "",
    "isForged": 1,
    "isNewInvest": 0,
    "tokenWap": "",
    "tokenPc": "",
    "tokenOverTimeWap": 0,
    "tokenOverTimePc": 0,
    "loginTime": "",
    "loginOsType": 0,
    "vip": 0,
    "email": "",
    "BACKUPID": 0,
    "apiV": "",
    "appV": ""
    }
    }],
    "loan": {
    "loanID": 388,
    "mobile": "18989706775",
    "name": "金碎妹",
    "money": "190000.00",
    "year": "18.00",
    "certType": 1,
    "certContent": "330302195810051220",
    "pledge": "",
    "financingEntity": "",
    "usage": "资金周转",
    "addTime": "2018-02-08 11:46:22",
    "updateTime": "2018-02-08 11:46:22",
    "status": 1,
    "alias": "",
    "imageUrl": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/02\/08\/PEy3iz3KJv.jpg",
    "info": "本项目为个人资金周转借款，借款人以名下车辆(梅赛德斯-奔驰汽车)为抵质押物，还款来源有保障。借款人户籍所在地位于江西省。交易过程中佳乾财富平台绝不触碰投资人资金，资金由富友支付第三方支付平台全程托管，并在富友支付及银行的全程托管下直接在投资人及借款人账户流动。",
    "pledgeType": 1,
    "contractImageUrl": "http:\/\/slb.dahengdian.com\/jiaqiancaifu\/2018\/02\/08\/fcFMRLKvSv.jpg",
    "beginTime": 0,
    "endTime": 0
    }
    }
    }
     * @apiUse CreateUserError
     */