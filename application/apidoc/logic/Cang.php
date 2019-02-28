<?php

    /**
     * @api {post} cang/create 投资一个标的
     * @apiVersion 1.0.0
     * @apiName create
     * @apiDescription 投资一个标的
     * @apiGroup Cang
     *
     * @apiParam {String} moneySubject 投资金额（相对标的金额，非本金，非支付金额）
     * @apiParam {Array} [hongbaoIDS] 红包ID列表
     * @apiParam {Number} subjectID 标的ID
     * @apiParamExample {json} 发送报文:
    {
    "app": {
    "moneySubject": "1000.00",
    "hongbaoIDS": [12,13],
    "subjectID": 19
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     *
     * @apiSuccess {Number} cangID 新生成的持仓ID
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "cangID": "6"
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */

    /**
     * @api {post} cang/getCangList 得到我的投资列表
     * @apiVersion 1.0.0
     * @apiName getCangList
     * @apiDescription 得到我的投资列表
     * @apiGroup Cang
     *
     * @apiParam {Number=[1,2]} status 1为持有中，2为已回款
     * @apiParamExample {json} 发送报文:
    {
    "status": 1
    }
     *
     * @apiSuccess {Object[]} cangList 我的投资列表
     * @apiSuccess {Number} cangList.cangID ID
     * @apiSuccess {Number} cangList.moneySubject 投资份额（非本金，非实际支付金额）
     * @apiSuccess {Number} cangList.status 状态，1-已持有，4-已计息，5-已回款
     * @apiSuccess {String} cangList.statusText 状态描述，参考状态
     * @apiSuccess {String} cangList.payTime 交易时间
     * @apiSuccess {String} cangList.interestBeginTime 起息日期
     * @apiSuccess {String} cangList.interestEndTime 停止计息日
     * @apiSuccess {String} cangList.repayTimeText 回款时间描述
     * @apiSuccess {String} cangList.year 年化
     * @apiSuccess {String} cangList.yearExt 追加年化
     * @apiSuccess {String} cangList.yearSystem 系统年化（废弃）
     * @apiSuccess {String} cangList.ben 实际投入本金
     * @apiSuccess {String} cangList.interest 利息
     * @apiSuccess {Number} cangList.investDay 投资天数
     * @apiSuccess {Object} cangList.subject 相关标的
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Number} cangList.subject.subjectTypeID 标的类型
     * @apiSuccess {Number} cangList.subject.interestTypeID 计息类型
     * @apiSuccess {Number} cangList.subject.interestTimeTypeID 计息时间类型
     * @apiSuccess {String} cangList.subject.reachTime 标的标题
     * @apiSuccess {String} cangList.subject.title 标的标题
     * @apiSuccess {Object[]} cangList.cangRepay 预回款清单
     * @apiSuccess {String} cangList.cangRepay.money 回款金额
     * @apiSuccess {String} cangList.cangRepay.repayTime 回款类型
     * @apiSuccess {String} cangList.cangRepay.resultTime 实际到款时间
     * @apiSuccess {String} cangList.cangRepay.reachTime 到款时间
     * @apiSuccess {Number} cangList.cangRepay.status 清单状态，1-未回款，2-已回款，3-已到账（该状态可隐藏）
     * @apiSuccess {Number} cangList.cangRepay.repayTypeID 清单类型，1-本金，2-利息
     * @apiSuccessExample {json} 返回json数据（举例）:
    {
    "code": 1,
    "msg": "操作成功",
    "content": {
    "cangList": [
    {
    "cangID": 33,
    "moneySubject": "2000.00",
    "status": 4,
    "payTime": "2017-12-12 20:53:50",
    "interestBeginTime": "2017-12-12",
    "interestEndTime": "2017-12-23",
    "year": "12.80",
    "yearExt": "2.00",
    "yearSystem": "2.00",
    "ben": "2000.00",
    "interest": "7.01",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "2000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "7.01",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    },
    {
    "cangID": 30,
    "moneySubject": "98000.00",
    "status": 4,
    "payTime": "2017-12-12 18:28:27",
    "interestBeginTime": "2017-12-12",
    "year": "12.80",
    "ben": "98000.00",
    "interest": "343.67",
    "investDay": 10,
    "subject": {
    "subjectTypeID": 2,
    "interestTypeID": 1,
    "interestTimeTypeID": 1,
    "title": "普通标11111"
    },
    "cangRepay": [
    {
    "money": "98000.00",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 1
    },
    {
    "money": "343.67",
    "repayTime": "2017-12-22",
    "reachTime": "2017-12-23",
    "resultTime": "2017-12-12",
    "status": 1,
    "repayTypeID": 2
    }
    ],
    "statusText": "计息中"
    }
    ]
    },
    "sign": "4297f44b13955235245b2497399d7a93"
    }
     * @apiUse CreateUserError
     */
