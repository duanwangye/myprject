{include file="public/header-begin" /}
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/style.min.css?v=4.0.0">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/steps/jquery.steps.css">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/chosen/chosen.css">
<script src="{$Think.config.base_url}/static/common/js/vue.js"></script>
{include file="public/header-end" /}
<div id="vue" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>价格查询工具</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="get" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品类型</label>
                            <div class="col-sm-2">
                                <select v-model="queryPriceParam.ptype" class="form-control input-sm select-sm">
                                    <option value="1" selected>接机</option>
                                    <option value="2">送机</option>
                                    <option value="3">包车</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">区域</label>
                            <div class="col-sm-2">
                                <select v-model="queryPriceParam.ktype" class="form-control input-sm select-sm">
                                    <option value="0" selected>市区</option>
                                    <option value="1">郊区</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">是否跨城</label>
                            <div class="col-sm-2">
                                <select v-model="queryPriceParam.ctype" class="form-control input-sm select-sm">
                                    <option value="0" selected>否</option>
                                    <option value="1">是</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">用车时间</label>
                            <div class="col-sm-10">
                                <input v-model="queryPriceParam.usetime" type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发城市</label>
                            <div class="col-sm-10">
                                <div class="form-group input-group" style="width:100%; margin-left: 0; margin-bottom: 0">
                                    <input id="area" data-id="" placeholder="在这里输入城市关键词，比如曼谷" type="text" class="form-control input-sm" value="" v-el:area>
                                    <div class="input-group-btn">
                                        <button style="margin-bottom: 0" type="button" class="btn btn-sm btn-white dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发地址</label>
                            <div class="col-sm-10">
                                <input v-model="queryPriceParam.route.from_name" type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发详细地址</label>
                            <div class="col-sm-10">
                                <input v-model="queryPriceParam.route.from_detail" type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的城市</label>
                            <div class="col-sm-10">
                                <div class="form-group input-group" style="width:100%; margin-left: 0; margin-bottom: 0">
                                    <input id="toCity" data-id="" placeholder="在这里输入城市关键词，比如曼谷" type="text" class="form-control input-sm" value="" v-el:toCity>
                                    <div class="input-group-btn">
                                        <button style="margin-bottom:0;" type="button" class="btn btn-sm btn-white dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的地址</label>
                            <div class="col-sm-10">
                                <input v-model="queryPriceParam.route.to_name" type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的详细地址</label>
                            <div class="col-sm-10">
                                <input v-model="queryPriceParam.route.to_detail" type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-12 col-sm-offset-2">
                                <button class="btn btn-primary" type="button" @click="queryPrice">查询价格</button>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">行程统计</label>
                            <div class="col-sm-10 control-label">
                               预计公里数/套餐公里数/超出公里数&nbsp;&nbsp;&nbsp;&nbsp; {{(queryPriceResult.distance.distance.value)/1000}} 公里 / {{(queryPriceResult.addroute.free_distance)/1000}} 公里 / {{(queryPriceResult.distance.distance.value - queryPriceResult.addroute.free_distance)/1000}} 公里
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <div class="col-sm-10 control-label">
                                预计时间/套餐时间/超出时间&nbsp;&nbsp;&nbsp;&nbsp; {{((queryPriceResult.distance.duration.value)/60).toFixed(2)}} 小时 / {{((queryPriceResult.addroute.free_time)/60).toFixed(2)}} 小时 / {{((queryPriceResult.distance.duration.value - queryPriceResult.addroute.free_time)/60).toFixed(2)}} 小时
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <div class="col-sm-10 control-label">
                                预计总金额/套餐金额/超出里程费用&nbsp;&nbsp;&nbsp;&nbsp; {{_amount}} 元 / {{((queryPriceResult.addroute.free_time)/60).toFixed(2)}} 元 / {{((queryPriceResult.distance.duration.value - queryPriceResult.addroute.free_time)/60).toFixed(2)}} 元
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>订购信息</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">产品类型</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" readonly value="{{filterPtypename(queryPriceResult.ptype)}}">
                            </div>
                            <label class="col-sm-2 control-label">是否跨城</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.ctype ? '是' : '否'}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">用车时间</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.usetime}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发城市</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.from_cityname}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.from_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">出发详细地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.from_detail}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的城市</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.to_cityname}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.to_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的详细地址</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" readonly value="{{queryPriceResult.route.to_detail}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">车型列表</label>
                            <div class="col-sm-9">
                                <select id="carType" data-placeholder="选择车型" class="form-control input-sm select-sm chosen-select" style="height: 30px;width:100%;" v-model="carTypeid" v-el:carType>
                                    <option v-for="option in queryPriceResult.cartype_list" v-bind:value="option.cartypeid" hassubinfo="true">
                                        {{ option.name }} | 人数 {{ option.person }} | 行李 {{ option.luggage }} | 价格 {{ option.price }}
                                    </option>
                                </select>
                            </div>
                            <label class="col-sm-1 control-label" @click="caradd()">[选择]</label>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">已选车型</label>
                            <div class="col-sm-10" style="position: relative;">
                                <input type="text" class="form-control input-sm" value="{{_cartypes}}" readonly>
                                <div style="position: absolute; right: 20px;top: 5px;"><a href="javascript://" @click="clearCar()">[重选车型]</a></div>
                            </div>
                        </div>


                        <div class="form-group" v-if="queryPriceResult.hasOwnProperty('addservice')">
                            <label class="col-sm-2 control-label">{{placeOrderParam.addservice.child_chair1.name}}</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control input-sm" v-model="placeOrderParam.addservice.child_chair1.count">
                            </div>


                            <label class="col-sm-2 control-label">{{placeOrderParam.addservice.child_chair2.name}}</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control input-sm" v-model="placeOrderParam.addservice.child_chair2.count">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group" v-if="queryPriceResult.hasOwnProperty('airport_list') && (queryPriceResult.ptype==1 || queryPriceResult.ptype==2)">
                            <label class="col-sm-2 control-label">机场信息</label>
                            <div class="col-sm-4">
                                <select class="form-control input-sm select-sm" v-model="placeOrderParam.airport.airportid">
                                    <option v-for="option in queryPriceResult.airport_list" v-bind:value="option.airportid">
                                        {{ option.airportname }}
                                    </option>
                                </select>
                            </div>


                            <label class="col-sm-2 control-label">举牌接机</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" v-model="placeOrderParam.addservice.flight_pickup.title">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">出行信息</label>
                            <div class="col-sm-10">
                                <table width="100%">
                                    <tr>
                                        <td><span>总人数</span></td>
                                        <td><input type="number" style="width:80px" class="form-control input-sm" v-model="placeOrderParam.person"></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td><span>儿童数</span></td>
                                        <td><input type="number" style="width:80px" class="form-control input-sm" value="{{placeOrderParam.child}}"></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td><span>行李数</span></td>
                                        <td><input type="number" style="width:80px" class="form-control input-sm" value="{{placeOrderParam.luggage}}"></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td><span>用车天数</span></td>
                                        <td><input type="number" style="width:80px" class="form-control input-sm" value="{{placeOrderParam.cardays}}"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系人姓名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" v-model="placeOrderParam.contact.truename">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系人电话</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" v-model="placeOrderParam.contact.phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">备用电话</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" v-model="placeOrderParam.contact.phone2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">微信</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-sm" v-model="placeOrderParam.contact.wechat">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">联系人备注</label>
                            <div class="col-sm-10">
                                <textarea v-model="placeOrderParam.contact.remark" style="width: 100%; margin-top: 7px;height: 60px;border: 1px solid #e5e6e7;"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12 col-sm-offset-2">
                                <button class="btn btn-primary" type="button" @click="placeOrder">提交订单</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!--
<div class="modal inmodal" id="modal-watch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">选择车型（如果一辆车，则选中其中一辆即可）</h4>
            </div>

            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>用车时间</th>
                            <th>用车天数</th>
                            <th>总人数</th>
                            <th>儿童数</th>
                            <th>0~4岁童座</th>
                            <th>5~7岁童座</th>
                            <th>8-12童座</th>
                        </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td valign="middle">
                            <select id="carType" data-placeholder="选择车型" class="form-control input-sm select-sm chosen-select" style="height: 30px;width: 150px" v-model="carTypeid" v-el:carType>
                                <option v-for="option in cartype" v-bind:value="option.id" hassubinfo="true">
                                    {{ option.name }}
                                </option>
                            </select>
                        </td>
                        <td><input v-model="addroute.usetime" type="datetime-local" class="form-control input-sm"></td>
                        <td><input v-model="addroute.cardays" type="number" class="form-control input-sm"></td>
                        <td><input v-model="addroute.person" type="number" class="form-control input-sm"></td>
                        <td><input v-model="addroute.child" type="number" class="form-control input-sm"></td>
                        <td><input v-model="addroute.usetime" type="number" class="form-control input-sm"></td>
                        <td><input v-model="addroute.usetime" type="number" class="form-control input-sm"></td>
                        <td><input v-model="addroute.usetime" type="number" class="form-control input-sm"></td>
                    </tr>

                    <tr v-if="cartype.length == 0">
                        <td>该城市还没有配置车型信息</td>
                    </tr>

                    </tbody>
                </table>


                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>用车时间</th>
                        <th>用车天数</th>
                        <th>乘车人数</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="routeitem of item.addroute">
                        <td valign="middle">
                            {{ routeitem.cartypeid }}
                        </td>
                        <td>
                            {{ routeitem.cartype }}
                        </td>
                        <td>
                            {{ routeitem.cardays }}
                        </td>
                        <td>
                            {{ routeitem.person }}
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
-->

{include file="public/footer-js" /}
<script src="{$Think.config.base_url}/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script>
    new Vue({
        el: 'body',
        data: {
            queryPriceParam:{
                route:{
                    from_cityid:0,
                    from_name: '素万那普国际机场',
                    from_detail: '素万那普国际机场',
                    to_cityid:0,
                    to_name: '白宫大酒店',
                    to_detail: 'Phetchaburi 15 Alley, Thanon Phaya Thai, Ratchathewi, Bangkok',
                }
            },
            queryPriceResult:{
                //usetime:'123123123',
                route:{},
                addroute:{}
            },
            placeOrderParam:{
                usetime:'',
                ptype:1,
                ctype:0,
                ktype:0,
                person:0,
                child:0,
                luggage:0,
                cardays:1,
                amount_original:0,
                amount:0,
                contact:{},
                cartype_list:[],
                route:{},
                addservice:{
                    child_chair1:{
                        keyword:"",
                        name:"",
                        count:0,
                        price:0
                    },
                    child_chair2:{
                        keyword:"",
                        name:"",
                        count:0,
                        price:0
                    },
                    flight_pickup:{
                        keyword:"",
                        name:"",
                        title:'',
                        price:0
                    }
                },
                airport:{}
            }

        },
        computed : {
            _airportItem: function() {
                for (var item of this.queryPirceResult.airport_list) {
                    if(item.airportid == this.placeOrder.airportid) {
                        return item;
                    }
                }
                return null;
            },
            _cartypes: function () {
                var rescars = '';
                for(var item of this.placeOrderParam.cartype_list) {
                    rescars = rescars + item.name + '、'
                }
                return rescars.substring(0,rescars.length-1);
            },
            _amount_original: function () {
                var amount = 0;

                return amount;
            },
            _amount: function () {
                var amount = 0;
                //计算车型价格
                for(var item of this.placeOrderParam.cartype_list) {
                    amount += item.price * 1
                }

                console.log(this.placeOrderParam.cartype_list);

                //计算夜间服务费
                //if(this.queryPriceResult.addroute.hasOwnProperty('night')) {
                    amount += this.queryPriceResult.addroute.night  * 1;
                //}

                //附加服务费
                amount += this._amountAddservice  * 1;
                return amount;
            },
            _amountAddservice: function () {
                var amount = 0;
                if(this.queryPriceResult.hasOwnProperty('addservice')) {
                    if(this.queryPriceResult.addservice.hasOwnProperty('child_chair1')) {
                        var chair = this.queryPriceResult.addservice.child_chair1;
                        for(var item of chair) {
                            amount += this.queryPriceResult.addservice.child_chair1.price * this.placeOrderParam.addservice.child_chair1.count * 1;
                        }
                        console.log(amount);
                    }


                    return amount;
                }
                return 0;
            }
        },
        methods: {
            queryPrice: function () {
                var that = this;
                var param = {};
                param.ptype = this.queryPriceParam.ptype;
                param.ctype = this.queryPriceParam.ctype;
                param.ktype = this.queryPriceParam.ktype;
                param.route = {};
                param.route.from_cityid = this.queryPriceParam.route.from_cityid;
                param.route.from_cityname = this.queryPriceParam.route.from_cityname;
                param.route.from_name = this.queryPriceParam.route.from_name;
                param.route.from_detail = this.queryPriceParam.route.from_detail;

                param.route.to_cityid = this.queryPriceParam.route.to_cityid;
                param.route.to_cityname = this.queryPriceParam.route.to_cityname;
                param.route.to_name = this.queryPriceParam.route.to_name;
                param.route.to_detail = this.queryPriceParam.route.to_detail;

                $.ajax({
                    url: '{:url('city/queryPrice')}',
                    data:{data:param},
                    type: 'post',
                    dataType: 'json',
                }).done(function (data) {
                    if (data.code == 1) {
                        //保存原始返回数据
                        that.queryPriceResult = data.content;

                        //this.placeOrderParam.addservice
                        if(that.queryPriceResult.hasOwnProperty('addservice')) {
                            if(that.queryPriceResult.addservice.hasOwnProperty('child_chair1')) {
                                that.placeOrderParam.addservice.child_chair1.keyword =  that.queryPriceResult.addservice.child_chair1.keyword;
                                that.placeOrderParam.addservice.child_chair1.name =  that.queryPriceResult.addservice.child_chair1.name;
                                that.placeOrderParam.addservice.child_chair1.count =  0;
                                that.placeOrderParam.addservice.child_chair1.price =  0;
                            }

                            if(that.queryPriceResult.addservice.hasOwnProperty('child_chair2')) {
                                that.placeOrderParam.addservice.child_chair2.keyword =  that.queryPriceResult.addservice.child_chair2.keyword;
                                that.placeOrderParam.addservice.child_chair2.name =  that.queryPriceResult.addservice.child_chair2.name;
                                that.placeOrderParam.addservice.child_chair2.count =  0;
                                that.placeOrderParam.addservice.child_chair2.price =  0;
                            }

                            if(that.queryPriceResult.addservice.hasOwnProperty('flight_pickup')) {
                                that.placeOrderParam.addservice.flight_pickup.keyword =  that.queryPriceResult.addservice.flight_pickup.keyword;
                                that.placeOrderParam.addservice.flight_pickup.name =  that.queryPriceResult.addservice.flight_pickup.name;
                                that.placeOrderParam.addservice.flight_pickup.title =  '';
                                that.placeOrderParam.addservice.flight_pickup.price =  0;
                            }
                        }

                        setTimeout(function () {
                            that.carSelect();
                        }, 300);
                    }
                    else {
                        swal(data.msg);
                        req2.reject();//非常好，不再走下一个then
                    }
                });
            },
            placeOrder: function () {

                this.placeOrderParam.usetime = this.queryPriceResult.usetime;
                this.placeOrderParam.ptype = this.queryPriceResult.ptype;
                this.placeOrderParam.ctype = this.queryPriceResult.ctype;
                this.placeOrderParam.ktype = this.queryPriceResult.ktype;

                //this.placeOrderParam.cardays
                //this.placeOrderParam.person
                //this.placeOrderParam.child
                //this.placeOrderParam.luggage
                //this.placeOrderParam.amount_original
                //this.placeOrderParam.amount
                //this.placeOrderParam.promotionid
                this.placeOrderParam.route = this.queryPriceResult.route;

                //this.placeOrderParam.cartype_list
                for(var item of this.placeOrderParam.cartype_list) {
                    console.log(item);
                }







                var param = {};
                param.ptype = this.queryPriceResult.ptype;
                param.ctype = this.queryPriceResult.ctype;
                param.route = this.queryPriceResult.route;
                //param.route


                param.route.from_cityid = this.queryPriceParam.route.from_cityid;
                param.route.from_cityname = this.queryPriceParam.route.from_cityname;
                param.route.from_name = this.queryPriceParam.route.from_name;
                param.route.from_detail = this.queryPriceParam.route.from_detail;

                param.route.to_cityid = this.queryPriceParam.route.to_cityid;
                param.route.to_cityname = this.queryPriceParam.route.to_cityname;
                param.route.to_name = this.queryPriceParam.route.to_name;
                param.route.to_detail = this.queryPriceParam.route.to_detail;

                $.ajax({
                    url: '{:url('city/placeOrder')}',
                    data:{data:param},
                    type: 'post',
                    dataType: 'json',
                }).done(function (data) {
                    if (data.code == 1) {
                        this.queryPriceResult = data.content;
                    }
                    else {
                        swal(data.msg);
                        req2.reject();//非常好，不再走下一个then
                    }
                });
            },
            changeStep: function(step) {
                if(step > 0 && this.step == 4) {
                    return;
                }

                if(step < 0 && this.step == 1) {
                    return;
                }

                if(step > 0 && this.step == 1) {
                    this.getDistancematrix();
                }

                this.step += step;
            },
            thisStep: function (step) {
                this.step = step;
            },
            getDistancematrix: function() {
                var that = this;
                var param;
                //接机
                if(this.item.ptype == 1) {
                    param = {
                        from_name: this._airportItem.name_cn,//起始地址
                        from_city: this.city.name_cn,//起始城市名称
                        to_name: this.item.address.to_name,//到达地址
                        to_city: this.city.name_cn,//到达城市名称
                        to_detail: this.item.address.to_detail,//到达详细地址
                    };
                }
                //送机
                else if(this.item.ptype == 2){
                    param = {
                        from_name: this.item.to_name,//起始地址
                        from_city: this._airportItem.name_cn,//起始城市名称
                        to_name: this.item.to_name,//到达地址
                        to_city: this.city.name_cn,//到达城市名称
                    };
                }

                $.ajax({
                    url: '{:url('map/getDistancematrix')}',
                    data: param,
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 1) {
                            that.distancematrix = data.content;
                        }
                        else {
                            swal("机场" + data.msg);
                        }
                    }
                });
            },
            getPartnerList:function () {
                var that = this;
                $.ajax({
                    url: '{:url('system/getPartnerList')}',
                    data: [],
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 1) {
                            that.partnerList = data.content;
                        }
                        else {
                            swal(data.msg);
                        }
                    }
                });
            },
            amountCopy: function () {
                this.item.amount = this._amount;
            },
            addOrder: function () {

                //接机
                if(this.item.ptype == 1) {
                    this.item.address.from_name = this._airportItem.name_cn;//起始地址
                    this.item.address.from_detail = this._airportItem.name_cn;//起始地址
                    this.item.address.from_cid = this.city.cityid;//起始城市名称
                    this.item.address.from_city = this.city.name_cn;//起始城市名称
                    this.item.address.from_x = this._airportItem.x;//经度
                    this.item.address.from_y = this._airportItem.y;//维度


                    this.item.address.to_name = this.item.to_name;//到达地址
                    this.item.address.to_detail = this.item.to_detail;//到达详细地址
                    this.item.address.to_cid = this.city.cityid;//到达城市名称
                    this.item.address.to_city = this.city.name_cn;//到达城市名称
                    this.item.address.to_x = 0;//经度
                    this.item.address.to_y = 0;//维度
                }

                //送机
                else if(this.item.ptype == 2) {

                }


                this.item.addroute.cartypeid = this._carTypeItem.id;
                this.item.addroute.cartype = this._carTypeItem.name;
                this.item.addroute.price = this._carTypeItem.price;
                this.item.addroute.carcount = this._carTypeItem.carcount;
                this.item.addroute.over_time_price = this._over_time_price;
                this.item.addroute.over_distance_price = this._over_distance_price;
                this.item.addroute.estimated_time = this.distancematrix.duration.value;
                this.item.addroute.estimated_distance = this.distancematrix.distance.value;
                this.item.addroute.over_time = (this.distancematrix.duration.value - this.item.addroute.free_time) * 1 < 0 ? 0 : (this.distancematrix.duration.value - this.item.addroute.free_time) * 1;
                this.item.addroute.over_distance = (this.distancematrix.distance.value - this.item.addroute.free_distance ) * 1 < 0 ? 0 : (this.distancematrix.distance.value - this.item.addroute.free_distance) * 1;

                var that = this;
                $.ajax({
                    url: '{:url('order/addOrder')}',
                    data: this.item,
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 1) {
                            swal(data.msg);
                        }
                        else {
                            swal(data.msg);
                        }
                    }
                });
            },
            caradd:function () {
                var cartypeid = 0;
                $(this.$els.cartype).find("option:selected").each(function(){
                    cartypeid = $(this).val();
                }); //这里得到的就是	});
                for (var item of this.queryPriceResult.cartype_list) {
                    if(item.cartypeid == cartypeid) {
                        this.placeOrderParam.cartype_list.push(item);
                        return;
                    }
                }

                return {
                    price :0
                };
            },
            clearCar: function () {
                this.placeOrderParam.cartype_list = [];
            },
            carSelect: function () {
                var that = this;
                $("#carType").chosen({
                    no_results_text: "没有找到！",
                    allow_single_deselect: true,
                    display_disabled_options: false
                }).change(function(){
                    that.carTypeid
                });;
                $("#carType").trigger("liszt:updated");
                //$(".dept-select").chosen().
            },
            showSelect: function () {
                $(this.$els.cartype).find("option:selected").each(function(){
                    alert($(this).val());
                }); //这里得到的就是	});
            },
            filterPtypename:function (ptype) {
                if(ptype == 1) {
                    return '接机';
                }
                else if(ptype == 2) {
                    return '送机';
                }
                else if(ptype == 3) {
                    return '包车';
                }
            }
        },
        ready : function () {
            var that = this;
            //this.getPartnerList();
            $("#area").bsSuggest({
                indexId: 0,
                indexKey: 1,
                getDataMethod: "url",
                effectiveFieldsAlias: {
                    cityid: "编号",
                    name_cn: "城市",
                    name_la: "地区",
                },
                showHeader: true,
                data: {
                    value:[]
                },
                url: "{:url('city/getCityListByKeyword')}" + "/?keyword=",
                processData: function(json) {
                    var i, len, data = {
                        value: []
                    };

                    if (json.code!=1) {
                        return;
                    }
                    len = json.content.length;

                    for (i = 0; i < len; i++) {
                        data.value.push({
                            "cityid": json.content[i]['cityid'],
                            "name_cn": json.content[i]['name_cn'],
                            "name_la": json.content[i]['name_la'],
                        })
                    }
                    return data;
                }
            }).on('onSetSelectValue', function (e, keyword) {
                that.queryPriceParam.route.from_cityid = keyword.id;
                that.queryPriceParam.route.from_cityname = keyword.key;
            });

            $("#toCity").bsSuggest({
                indexId: 0,
                indexKey: 1,
                getDataMethod: "url",
                effectiveFieldsAlias: {
                    cityid: "编号",
                    name_cn: "城市",
                    name_la: "地区",
                },
                showHeader: true,
                data: {
                    value:[]
                },
                url: "{:url('city/getCityListByKeyword')}" + "/?keyword=",
                processData: function(json) {
                    var i, len, data = {
                        value: []
                    };

                    if (json.code!=1) {
                        return;
                    }
                    len = json.content.length;

                    for (i = 0; i < len; i++) {
                        data.value.push({
                            "cityid": json.content[i]['cityid'],
                            "name_cn": json.content[i]['name_cn'],
                            "name_la": json.content[i]['name_la'],
                        })
                    }
                    return data;
                }
            }).on('onSetSelectValue', function (e, keyword) {
                that.queryPriceParam.route.to_cityid = keyword.id;
                that.queryPriceParam.route.to_cityname = keyword.key;
            });
        },
    })
</script>
<style>
    .chosen-container-single .chosen-single { padding-top: 3px;padding-bottom: 0;padding-left:10px;}
    .chosen-container-single .chosen-single div b {  background-position: 0px 4px;  }
    .chosen-container-active.chosen-with-drop .chosen-single div b {  background-position-y:4px;  }

    .chosen-container-multi .chosen-choices {
        padding: 0;
    }
    .chosen-container-active .chosen-choices {
        border: 1px solid #ccc;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0.3);
    }
    .chosen-container-multi .chosen-results {
        padding:4px;
    }
    .chosen-container .chosen-results li {

    }
</style>
<script src="{$Think.config.base_url}/static/admin/js/plugins/chosen/chosen.jquery.js"></script>



<script>

</script>

{include file="public/footer" /}