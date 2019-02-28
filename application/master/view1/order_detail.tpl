{include file="public/header-begin" /}
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/style.min.css?v=4.0.0">
<script src="{$Think.config.base_url}/static/common/js/vue.js"></script>
{include file="public/header-end" /}
<div id="vue" class="wrapper wrapper-content animated fadeInRight">
    <div  class="row">
        <div class="col-sm-8">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>订单信息</h5>
                    <div class="ibox-tools">
                        <a data-toggle="modal" href="#modal-watch"  class="btn btn-primary btn-xs">跟踪</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>产品</td>
                            <td>[{{item.ptype_text}}] {{item.from_city}}->{{item.to_city}} | {{item.from_name}} -> {{item.to_name}}</td>
                            <td align="right">[关联]</td>
                        </tr>
                        <tr>
                            <td>ID/订单编号</td>
                            <td>{{item.orderid}}<span style="padding-left: 5px;padding-right: 5px">-</span>{{item.ordernumber}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>订单来源</td>
                            <td>{{item.partner_name}}<span style="padding-left: 5px;padding-right: 5px">-</span>{{item.partner_ordernumber}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>状态</td>
                            <td>
                                <span v-if="item.status == 1" class="label label-info">{{item.status_text}}</span>
                                <span v-if="item.status == 2" class="label label-danger">{{item.status_text}}</span>
                                <span v-if="item.status == 3" class="label label-success">{{item.status_text}}</span>
                                <span v-if="item.status == 4" class="label label-warning">{{item.status_text}}</span>
                                <span v-if="item.status == 5" class="label label-default">{{item.status_text}}</span>
                                <span v-if="item.status == 6" class="label label-danger">{{item.status_text}}</span>
                            </td>
                            <td align="right">
                                [修改]
                            </td>
                        </tr>
                        <tr>
                            <td>下单时间</td>
                            <td>{{item.addtime_text}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>更新时间</td>
                            <td>{{item.edittime_text}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td colspan="3"><b>产品订单</b></td>
                        </tr>

                        <tr>
                            <td>用车信息</td>
                            <td>用车时间 {{item.usetime}}&nbsp;&nbsp;&nbsp;&nbsp;用车天数 {{item.cardays}} 天</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>订购车型</td>
                            <td>{{item.cartype}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>出发地 -> 目的地</td>
                            <td>{{item.from_name}} -> {{item.to_name}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>出发详细地址</td>
                            <td>{{item.from_detail}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>目的详细地址</td>
                            <td>{{item.to_detail}}</td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>行程统计</td>
                            <td>
                                <span>预计共行驶 {{item.addroute.estimated_distance / 1000}} 公里</span>
                                <span> - 共需 {{item.addroute.estimated_time}} 分钟</span>
                                <br>
                                <span>预计超出套餐 {{item.addroute.over_distance / 1000}} 公里</span>
                                <span> - 超出套餐 {{item.addroute.over_time}} 分钟</span>
                                <br>
                                <span>套餐里程 {{item.addroute.free_distance / 1000}} 公里</span>
                                <span> - 套餐时间 {{item.addroute.free_time}} 分钟</span>
                            </td>
                            <td></td>
                        </tr>


                        <tr>
                            <td>人数</td>
                            <td>总人数 {{item.person}} 人&nbsp;&nbsp;&nbsp;&nbsp;儿童 {{item.child}} 人</td>
                            <td></td>
                        </tr>



                        <tr>
                            <td>总价格</td>
                            <td>￥ {{item.amount}} 元</td>
                            <td align="right">[明细]</td>
                        </tr>

                        <tr>
                            <td width="15%">备注</td>
                            <td>{{item.note}}</td>
                            <td width="10%" align="right">
                                <a data-toggle="modal" href="#modal-note">[修改]</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="ibox" v-if="item.status_pay == 1">
                <div class="ibox-title">
                    <h5>付款信息</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>值</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>付款金额</td>
                            <td>
                                ￥ {{item.finance.amount}} 元
                            </td>
                        </tr>
                        <tr>
                            <td>支付类型</td>
                            <td>{{item.finance.paytype}}</td>
                        </tr>
                        <tr v-if="item.finance.id">
                            <td>支付编号</td>
                            <td>{{item.finance.id}}</td>
                        </tr>
                        <tr v-if="item.finance.paytime">
                            <td>交易时间</td>
                            <td>{{item.finance.paytime}}</td>
                        </tr>
                        <tr v-if="item.finance.terminal">
                            <td>交易终端</td>
                            <td>{{item.finance.terminal}}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>联系人信息</h5>
                    <div class="ibox-tools">
                        <a data-toggle="modal" href="#modal-contact"  class="btn btn-primary btn-xs">修改</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>姓名</td>
                            <td>{{item.userinfo.truename}}</td>
                        </tr>
                        <tr>
                            <td>联系电话</td>
                            <td>{{item.userinfo.phone}}</td>
                        </tr>
                        <tr>
                            <td>备用电话</td>
                            <td>{{item.userinfo.phone2}}</td>
                        </tr>
                        <tr>
                            <td>微信</td>
                            <td>{{item.userinfo.wechat}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <h5>关联订单</h5>

                    <div class="ibox-tools">
                        <a data-toggle="modal" href="#modal-form"  class="btn btn-primary btn-xs">修改</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <p>
                     123123123123123
                    <p>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <h5>下单人</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>值</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>姓名</td>
                            <td>张奇</td>
                        </tr>
                        <tr>
                            <td>手机号</td>
                            <td>13136180523</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>





<div class="modal inmodal" id="modal-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">修改订单状态</h4>
            </div>
            <form id="formStatus" action="" method="post" class="form-horizontal">
                <input name="id" type="hidden" value="">
                <div class="modal-body" style="text-align: center;">

                        <h4 class="modal-title">【服务中】 -> 【待评价】</h4>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form id="formService" action="" method="post">
            <input name="id" type="hidden" value="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">指派司机</h4>
            </div>
            <div class="modal-body">
                <textarea name="content" id="content" style="width:100%;height:300px;">123123123123123123123</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-note" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form id="formNote" action="" method="post">
                <input name="id" type="hidden" value="">
                <div class="modal-header">
                    <h4 class="modal-title">修改备注</h4>
                </div>
                <div class="modal-body">
                    <textarea name="note" style="width:100%;height:200px;">123123123123</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-price" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form id="formPrice" action="" method="post" class="form-horizontal">
                <div class="modal-header">
                    <h4 class="modal-title">修改应付价格</h4>
                </div>
                <div class="modal-body">
                    <input name="id" type="hidden" value="">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">应付价格：</label>
                        <div class="col-sm-8">
                            <input name="price" type="text" placeholder="真实姓名" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-contact" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">修改联系人信息</h4>
            </div>
            <form id="formContact" action="" method="post" class="form-horizontal">
            <div class="modal-body">
                    <input name="id" type="hidden" value="">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系人姓名：</label>
                        <div class="col-sm-8">
                            <input type="truename" placeholder="真实姓名" v-model="item.userinfo.truename" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">联系电话：</label>

                        <div class="col-sm-8">
                            <input type="text" placeholder="手机号" v-model="item.userinfo.phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">备用电话：</label>

                        <div class="col-sm-8">
                            <input type="text" placeholder="微信号" v-model="item.userinfo.phone2" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">微信：</label>

                        <div class="col-sm-8">
                            <input type="text" placeholder="微信号" v-model="item.userinfo.wechat" class="form-control">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-watch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">订单跟踪</h4>
            </div>

            <div class="modal-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>操作</th>
                        <th>操作时间</th>
                        <th>操作人</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-for="track of trackList">
                        <td>{{track.title}}</td>
                        <td>{{track.addtime}}</td>
                        <td>{{track.operater}}</td>
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




{include file="public/footer-js" /}
<script>
    new Vue({
        el: '#vue',
        data: {
            id: {$id},
            item:{},
            trackList:{}
        },
        computed : {

        },
        methods: {
            getDetail:function() {
                var that = this;

                //锁定城市
                $.ajax({
                    url: '{:url('getDetail')}',
                    data: {
                        orderid: this.id
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 1) {
                            that.item = data.content;
                        }
                        else {
                            swal("订单" + data.msg);
                        }
                    }
                });


                //得到该城市车型列表
                $.ajax({
                    url: '{:url('city/getCartypeListOnEffective')}',
                    data: {
                        cityid: $(that.$els.area).attr('data-id'),
                        ptype: that.item.ptype,
                        usetime: that.item.usetime
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 1) {
                            that.carTypeList = data.content.carTypeList;
                            that.item.addroute = data.content.addroute;
                        }
                        else {
                            swal("车型" + data.msg);
                        }
                    }
                });


                if(this.item.ptype == 1 || this.item.ptype == 2) {
                    //得到该城市有效机场名称
                    $.ajax({
                        url: '{:url('city/getAirportList')}',
                        data: {
                            cityid: $(that.$els.area).attr('data-id')
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            if (data.code == 1) {
                                that.airportList = data.content;
                            }
                            else {
                                swal("机场" + data.msg);
                            }
                        }
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
                        to_name: this.item.to_name,//到达地址
                        to_city: this.city.name_cn,//到达城市名称
                        to_detail: this.item.to_detail,//到达详细地址
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
                    this.item.from_name = this._airportItem.name_cn;//起始地址
                    this.item.from_detail = this._airportItem.name_cn;//起始地址
                    this.item.from_cid = this.city.cityid;//起始城市名称
                    this.item.from_city = this.city.name_cn;//起始城市名称
                    this.item.from_x = this._airportItem.x;//经度
                    this.item.from_y = this._airportItem.y;//维度


                    this.item.to_name = this.item.to_name;//到达地址
                    this.item.to_detail = this.item.to_detail;//到达详细地址
                    this.item.to_cid = this.city.cityid;//到达城市名称
                    this.item.to_city = this.city.name_cn;//到达城市名称
                    this.item.to_x = 0;//经度
                    this.item.to_y = 0;//维度
                }
                //送机
                else if(this.item.ptype == 2) {

                }


                this.item.cartypeid = this._carTypeItem.id;
                this.item.cartype = this._carTypeItem.name;
                this.item.carprice = this._carTypeItem.price;
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
            }
        },
        ready : function () {
            this.getDetail();
        },
    })
</script>



<script src="{$Think.config.base_url}/static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="{$Think.config.base_url}/static/admin/js/bootstrap.min.js?v=3.3.5"></script>

<script charset="utf-8" src="{$Think.config.base_url}/static/admin/plugins/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="{$Think.config.base_url}/static/admin/plugins/kindeditor/lang/zh-CN.js"></script>
<script>
    KindEditor.ready(function (K) {
        window.editor = K.create('#content'
                , {
                    items: [
                        'source', 'forecolor', 'hilitecolor', 'bold',
                        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat'
                    ]
                });
    });
</script>
<script src="{$Think.config.base_url}/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="{$Think.config.base_url}/static/common/js/jquery.form.js"></script>
<script type="text/javascript">
    $(function() {
        var commonOptions = {
            beforeSubmit: function(arr, $form, options) {
                for (x of arr) {
                    if(x.name == 'content') {
                        x.value = editor.html();
                    }
                }
                $("button[data-dismiss='modal']").click();
            },
            success: function (data) {
                if(data.code==1) {
                    swal({
                        title: data.msg,
                        type: "success"
                    },function () {
                        window.location.href = data.forward;
                    })
                }
                else {
                    swal({
                        title: data.msg,
                        type: "warning"
                    })
                }
            }
        };
        $("#formService").ajaxForm(commonOptions);
        $("#formContact").ajaxForm(commonOptions);
        $("#formPrice").ajaxForm(commonOptions);
        $("#formNote").ajaxForm(commonOptions);
        $("#formStatus").ajaxForm(commonOptions);
    });
</script>
{include file="public/footer" /}