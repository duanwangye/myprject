{include file="public/header-begin" /}
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/style.min.css?v=4.0.0">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/steps/jquery.steps.css">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/chosen/chosen.css">
<script src="{$Think.config.base_url}/static/common/js/vue.js"></script>
{include file="public/header-end" /}
<div id="vue" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2>
                        订单录入
                    </h2>
                    <p>
                        &nbsp;
                    </p>


                    <div id="wizard" class="wizard clearfix">

                        <div class="steps clearfix">
                            <ul>
                                <li @click="thisStep(1)" class="{{step == 1  ? 'current' : 'disabled'}}">
                                    <a>
                                        <span class="number">1.</span>用车及行程信息</a></li>
                                <li @click="thisStep(2)" class="{{step == 2  ? 'current' : 'disabled'}}">
                                    <a>
                                        <span class="number">2.</span>行程及价格核算</a></li>
                                <li @click="thisStep(3)" class="{{step == 3  ? 'current' : 'disabled'}}">
                                    <a>
                                        <span class="number">3.</span>乘车联系人</a></li>
                                <li @click="thisStep(4)" class="{{step == 4  ? 'current' : 'disabled'}}">
                                    <a>
                                        <span class="number">4.</span>其他信息</a></li>
                            </ul>
                        </div>


                        <div class="content clearfix">
                            <div v-show="step == 1" class="step-content">
                                <div class="m-t-md">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2>用车及行程信息</h2>
                                            <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-12" style="padding-left: 0;">
                                                    <table class="zq_search">
                                                        <tr>
                                                            <td style="width:20%;padding-left: 15px;" valign="top">
                                                                <label>产品类型</label>
                                                                <select v-model="item.ptype" class="form-control input-sm select-sm" name="account">
                                                                    <option value="1" selected>接机</option>
                                                                    <option value="2">送机</option>
                                                                    <option value="3">包车</option>
                                                                </select>
                                                            </td>
                                                            <td style="width:60%;padding-left: 15px ;">
                                                                <label>城市</label>
                                                                <div class="form-group input-group" style="width:100%">
                                                                    <input id="area" data-id="" placeholder="在这里输入城市关键词，比如曼谷" type="text" class="form-control input-sm" value="" v-el:area>
                                                                    <div class="input-group-btn">
                                                                        <button type="button" class="btn btn-sm btn-white dropdown-toggle" data-toggle="dropdown">
                                                                            <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td style="width:100px;" align="left" valign="top">
                                                                <label>用车时间 *</label>
                                                                <input v-model="item.usetime" type="datetime-local" class="form-control input-sm">
                                                            </td>

                                                            <td style="width:100px;" align="right" valign="top">
                                                                <label>&nbsp;</label>
                                                                <button @click="getCitySomeInfo()" type="button" class="btn btn-sm btn-primary">加载车型</button>
                                                            </td>

                                                            <td style="width:50px;" align="right" valign="top">

                                                            </td>

                                                            <td align="left" valign="top">
                                                                    <label>车型 *</label>
                                                                    <select id="carType" data-placeholder="选择车型" class="form-control input-sm select-sm chosen-select" style="height: 30px;width:150px;" v-model="carTypeid" v-el:carType>
                                                                        <option v-for="option in cartype" v-bind:value="option.id" hassubinfo="true">
                                                                            {{ option.name }}
                                                                        </option>
                                                                    </select>
                                                            </td>
                                                            <td style="width:100px;" align="right" valign="top">
                                                                <label>&nbsp;</label>
                                                                <button @click="caradd()" type="button" class="btn btn-sm btn-primary">选中车型</button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-1" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>总人数 *</label>
                                                        <input type="number" v-model="item.person" class="form-control input-sm">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>行李数 *</label>
                                                        <input type="number" v-model="item.luggage" class="form-control input-sm">
                                                    </div>
                                                </div>
                                                <div class="col-sm-1" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>儿童数 *</label>
                                                        <input type="number" v-model="item.child" class="form-control input-sm">
                                                    </div>
                                                </div>
                                                <div v-if="item.addservice.hasOwnProperty('child_chair')" class="col-sm-1" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>{{item.addservice.child_chair[0].name}}</label>
                                                        <input type="number" v-model="item.addservice.child_chair[0].count" class="form-control input-sm">
                                                    </div>
                                                </div>
                                                <div v-if="item.addservice.hasOwnProperty('child_chair')" class="col-sm-1" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>{{item.addservice.child_chair[1].name}}</label>
                                                        <input type="number" v-model="item.addservice.child_chair[1].count" class="form-control input-sm">
                                                    </div>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div class="form-group" style="position: relative; ">
                                                        <label>已选车型 *</label>
                                                        <input type="text" class="form-control input-sm" value="{{_cartypes}}" readonly>
                                                        <div style="margin-top: 8px; position: absolute; right: 10px;top:20px"><a href="javascript://" @click="clearCar()">[重选车型]</a></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="item.ptype == 1 || item.ptype == 2" class="row">
                                                <div class="col-sm-6" style="padding-right: 0">
                                                    <div class="form-group">
                                                        <label>机场名称 *</label>
                                                        <select class="form-control input-sm select-sm" v-model="item.flight.aid">
                                                            <option v-for="option in airportList" v-bind:value="option.id">
                                                                {{ option.name_cn }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div v-if="item.ptype == 1 && item.addservice.hasOwnProperty('flight_pickup')" class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>举牌接机 </label>
                                                        <input type="text" class="form-control input-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div v-show="item.ptype == 1" class="col-sm-12 b-r">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>目的地址 *</label>
                                                        <input v-model="item.address.to_name" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="col-sm-8" style="margin-right: 0">
                                                    <div class="form-group">
                                                        <label>目的详细地址 *</label>
                                                        <input v-model="item.address.to_detail" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="item.ptype == 2" class="col-sm-12 b-r">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>出发地址 *</label>
                                                        <input id="address" name="address" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="col-sm-8" style="margin-right: 0">
                                                    <div class="form-group">
                                                        <label>出发详细地址 *</label>
                                                        <input id="address" name="address" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="item.ptype == 3" class="col-sm-12 b-r">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>上车地址 *</label>
                                                        <input id="address" name="address" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="col-sm-8" style="margin-right: 0">
                                                    <div class="form-group">
                                                        <label>上车详细地址 *</label>
                                                        <input id="address" name="address" type="text" class="form-control input-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="step == 2" class="step-content">
                                <div class="m-t-md">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h2>里程核算</h2>
                                            <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>预计行驶总里程</label>
                                                            <table width="100%">
                                                                <tr>
                                                                    <td valign="middle">
                                                                        <div class="input-group">
                                                                         <input type="text" class="form-control" v-model="distancematrix.distance.value"> <span class="input-group-addon">米</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="ibox-content" style="border:1px solid #cccccc">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>项目</th>
                                                                <th>数字</th>
                                                                <th>单位</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>预计总里程</td>
                                                                <td>{{distancematrix.distance.value/1000}}</td>
                                                                <td>公里</td>
                                                            </tr>
                                                            <tr>
                                                                <td>套餐里程</td>
                                                                <td>{{item.addroute.free_distance/1000}}</td>
                                                                <td>公里</td>
                                                            </tr>
                                                            <tr>
                                                                <td>预计超出里程</td>
                                                                <td>{{(distancematrix.distance.value - item.addroute.free_distance)/1000}}</td>
                                                                <td>公里</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <h2>时间核算</h2>
                                            <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>预计行驶总时间</label>
                                                        <table width="100%">
                                                            <tr>
                                                                <td valign="middle">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" v-model="distancematrix.duration.value"> <span class="input-group-addon">分钟</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="ibox-content" style="border:1px solid #cccccc">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>项目</th>
                                                                <th>数字</th>
                                                                <th>单位</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>预计行驶总时间</td>
                                                                <td>{{distancematrix.duration.value}}</td>
                                                                <td>分钟</td>
                                                            </tr>
                                                            <tr>
                                                                <td>套餐时间</td>
                                                                <td>{{item.addroute.free_time}}</td>
                                                                <td>分钟</td>
                                                            </tr>
                                                            <tr>
                                                                <td>预计超出时间</td>
                                                                <td>{{(distancematrix.duration.value - item.addroute.free_time)}}</td>
                                                                <td>分钟</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <h2>价格核算</h2>
                                            <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>预估总价格</label>
                                                        <table width="100%">
                                                            <tr>
                                                                <td valign="middle">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">¥</span><input type="text" class="form-control" v-model="_amount"> <span class="input-group-addon">元</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col-sm-1">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <table width="100%">
                                                            <tr>
                                                                <td valign="middle" style="height:34px">
                                                                    <a href="javascript://" @click="amountCopy()">-></a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        <label style="color: red">实际总价格</label>
                                                        <table width="100%">
                                                            <tr>
                                                                <td valign="middle">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">¥</span><input type="text" class="form-control" v-model="item.amount"> <span class="input-group-addon">元</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="ibox-content" style="border:1px solid #cccccc">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>项目</th>
                                                                <th>数字</th>
                                                                <th>单位</th>
                                                                <th>单元</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>套餐费用</td>
                                                                <td>{{_carTypeItem.price}}</td>
                                                                <td>￥</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>超里程费用</td>
                                                                <td>{{_over_distance_price}}</td>
                                                                <td>￥</td>
                                                                <td>￥{{item.addroute.over_distance_priceper}} 元/米</td>
                                                            </tr>
                                                            <tr>
                                                                <td>超时费用</td>
                                                                <td>{{_over_time_price}}</td>
                                                                <td>￥</td>
                                                                <td>￥{{item.addroute.over_time_priceper}} 元/分钟</td>
                                                            </tr>
                                                            <tr>
                                                                <td>夜间服务费用</td>
                                                                <td>{{item.addroute.night}}</td>
                                                                <td>￥</td>
                                                            </tr>
                                                            <tr>
                                                                <td>其他费用（举牌座椅等）</td>
                                                                <td>10</td>
                                                                <td>￥</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>合计</td>
                                                                <td>{{_amount}}</td>
                                                                <td>￥</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="step == 3" class="step-content">
                                <div class="m-t-md">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2>乘车联系人</h2>
                                            <div class="hr-line-dashed"></div>
                                            <div class="row">
                                                <div class="col-sm-12" style="padding-left: 0;">
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>联系人 *</label>
                                                            <input v-model="item.userinfo.truename" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2" style="margin-right: 0">
                                                        <div class="form-group">
                                                            <label>联系电话 *</label>
                                                            <input v-model="item.userinfo.phone" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2" style="margin-right: 0">
                                                        <div class="form-group">
                                                            <label>微信 </label>
                                                            <input v-model="item.userinfo.wechat" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2" style="margin-right: 0">
                                                        <div class="form-group">
                                                            <label>备用电话 </label>
                                                            <input v-model="item.userinfo.phone2" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4" style="margin-right: 0">
                                                        <div class="form-group">
                                                            <label>备注 </label>
                                                            <input v-model="item.userinfo.remark" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="step == 4" class="step-content">
                                <div class="m-t-md">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2>其他信息</h2>
                                            <div class="hr-line-dashed"></div>
                                                <div class="row">
                                                    <div class="col-sm-2" style="padding-right:0 ">
                                                        <div class="form-group">
                                                            <label>订单来源</label>
                                                            <select class="form-control  input-sm select-sm" v-model="item.partnerid">
                                                                <option v-for="option in partnerList" v-bind:value="option.id">
                                                                    {{ option.name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label>第三方订单号 *</label>
                                                            <input v-model="item.partner_ordernumber" type="text" class="form-control input-sm">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-7">
                                                        <div class="form-group">
                                                            <label>第三方订单生成时间 *</label>
                                                            <input v-model="item.partner_addtime" type="datetime-local" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="actions clearfix">
                            <ul>
                                <li class=" " >
                                    <a href="javascript://" @click="changeStep(-1)">上一步</a></li>
                                <li>
                                    <a href="javascript://" @click="changeStep(1)">下一步</a></li>
                                <li>
                                    <a href="javascript://" @click="addOrder()">完成</a></li>
                                <li>
                                    <a href="javascript://" @click="">取消</a></li>
                                <li>
                                    <a href="javascript://" @click="showSelect()">选择</a></li>
                            </ul>
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
<script>
    new Vue({
        el: 'body',
        data: {
            step:1,
            items:[],
            city:{},
            addroute:{},
            item: {
                //产品类型
                ptype:1,

                //航班
                flight:{
                    aid:0,
                    flightnum: '',
                },

                usetime:'',
                person: 0,
                luggage: 0,
                child:0,

                //地址信息
                address:{
                    from_name: '',
                    from_detail: '',
                    from_x: 0,
                    from_y: 0,
                    from_cid: 0,
                    from_city: '',
                    to_name: '白宫大酒店',
                    to_detail: 'Phetchaburi 15 Alley, Thanon Phaya Thai, Ratchathewi, Bangkok',
                    to_x: 0,
                    to_y: 0,
                    to_cid: 0,
                    to_city: '',
                },

                //addroute
                addroute:{},

                //addservice
                addservice: {},

                //car
                car: [],

                //联系人
                userinfo:{
                    truename:'',
                    phone:'',
                    phone2:'',
                    wechat:'',
                    remark:'',
                    cartype:'',
                },

                //合作商
                partner: {
                    partnerid:0,
                    partner_ordernumber:'',
                    partner_addtime:'',
                },

                //价值
                amount:0,
            },
            carTypeid:0,
            cartype:[],
            airportList
            airportList:[],
            distancematrix:{
                duration:{
                    value :0
                },
                distance:{
                    value :0
                }
            },
            partnerList:[],
        },
        computed : {
            _airportItem: function() {
                for (var item of this.airportList) {
                    if(item.id == this.item.aid) {
                        return item;
                    }
                }
                return null;
            },
            _carTypeItem: function() {
                for (var item of this.cartype) {
                    if(item.id == this.carTypeid) {
                        return item;
                    }
                }
                return {
                    price :0
                };
            },
            //儿童座椅
            _childChair: function () {
                var childChair = [];
                for(var item of this.addservice) {
                    if(item.keyword.indexOf('child_chair') >= 0) {
                        item.count = 0;
                        childChair.push(item);
                    }
                }
                return childChair;
            },
            _cartypes: function () {
                var rescars = '';
                for(var item of this.item.car) {
                    rescars = rescars + item.name + '、'
                }
                return rescars.substring(0,rescars.length-1);
            },
            _over_time_price: function () {
                return (this.item.addroute.over_time_priceper * (this.distancematrix.duration.value - this.item.addroute.free_time) * 1 < 0 ? 0 : this.item.addroute.over_time_priceper * (this.distancematrix.duration.value - this.item.addroute.free_time) * 1).toFixed(2);
            },
            _over_distance_price: function () {
                return ((this.distancematrix.distance.value * 1 - this.item.addroute.free_distance * 1)* 1 < 0 ? 0 : this.item.addroute.over_distance_priceper * (this.distancematrix.distance.value - this.item.addroute.free_distance)/1000 * 1).toFixed(2);
            },
            _amount: function () {
                if(this._carTypeItem == undefined) {
                    return 0;
                }
                return  (this._carTypeItem.price * 1 +
                        this._over_time_price * 1 +
                        this._over_distance_price * 1 +
                        this.item.addroute.night * 1).toFixed(2);
            },
            _partnerItem:function () {
                for (var item of this.partnerList) {
                    if(item.id == this.item.partnerid) {
                        return item;
                    }
                }
                return;
            }
        },
        methods: {
            getCitySomeInfo:function() {
                var that = this;
                var req1 = function(){
                    return $.ajax({
                        url: '{:url('city/getCityByCityid')}',
                        data: {
                            cityid: $(that.$els.area).attr('data-id')
                        },
                        type: 'post',
                        dataType: 'json'
                    }).then(function (data) {
                        if (data.code == 1) {
                            that.city = data.content;
                        }
                        else {
                            swal(data.msg);
                            //req1.reject();//非常好，不再走下一个then
                            throw null;
                        }
                    });
                }
                var req2 = function(){
                    return $.ajax({
                        url: '{:url('city/getCartypeListOnEffective')}',
                        data: {
                            cityid: $(that.$els.area).attr('data-id'),
                            ptype: that.item.ptype,
                            usetime: that.item.usetime
                        },
                        type: 'post',
                        dataType: 'json',
                    }).then(function (data) {
                        if (data.code == 1) {
                            that.cartype = data.content.cartype;
                            that.item.addroute = data.content.addroute;
                            that.item.addservice = data.content.addservice;

                            if(that.item.addservice.hasOwnProperty('child_chair')) {
                                for (var item of that.item.addservice.child_chair) {
                                    item.count = 0;
                                }
                            }
                            if(that.item.addservice.hasOwnProperty('flight_pickup')) {
                                that.item.addservice.flight_pickup.title = '';
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
                }
                var req3 = function(){
                    return $.ajax({
                        url: '{:url('city/getAirportList')}',
                        data: {
                            cityid: $(that.$els.area).attr('data-id')
                        },
                        type: 'post',
                        dataType: 'json',
                    }).then(function (data) {
                        if (data.code == 1) {
                            that.airportList = data.content;
                        }
                        else {
                            swal(data.msg);
                            req3.reject();//非常好，不再走下一个then
                        }
                    })
                }

                if(this.item.ptype == 1 || this.item.ptype == 2) {
                    req1().then(req2).then(req3).done(function () {
                        //console.log('请求发送完毕');
                    });
                }
                else {
                    req1().then(req2).done(function () {
                        //console.log('请求发送完毕');
                    });
                }
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
                console.log(this._carTypeItem);
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
                console.log(this.item);
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
                for (var item of this.cartype) {
                    if(item.id == cartypeid) {
                        this.item.car.push(item);
                        console.log(this.item.car);
                        return;
                    }
                }
                return {
                    price :0
                };
            },
            clearCar: function () {
                this.item.car = [];
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
                //$(that.$els.cartype).attr('data-id');
                //console.log(this.carTypeid);
                $(this.$els.cartype).find("option:selected").each(function(){
                    alert($(this).val());
                }); //这里得到的就是	});
            }
        },
        ready : function () {
            this.getPartnerList();
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


<script src="{$Think.config.base_url}/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script>
    $("#area").bsSuggest({
        indexId: 0,
        indexKey: 1,
        getDataMethod: "url",
        effectiveFieldsAlias: {
            cityid: "编号",
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
                    "name_la": json.content[i]['name_la'],
                })
            }
            return data;
        }
    });



    $("#toCity").bsSuggest({
        indexId: 0,
        indexKey: 1,
        getDataMethod: "url",
        effectiveFieldsAlias: {
            cityid: "编号",
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
                    "name_la": json.content[i]['name_la'],
                })
            }
            return data;
        }
    });
</script>

{include file="public/footer" /}