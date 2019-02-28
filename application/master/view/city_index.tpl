{include file="public/header-begin" /}
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/datapicker/datepicker3.css">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/plugins/chosen/chosen.css">
<link rel="stylesheet" href="{$Think.config.base_url}/static/admin/css/style.min.css?v=4.0.0">
<script src="{$Think.config.base_url}/static/common/js/vue.js"></script>
{include file="public/header-end" /}
<div class="wrapper wrapper-content">
    <div id="vue" class="row">
        <div class="col-sm-12 animated fadeInRight">
            <div class="mail-box-header">
                <div class="pull-right mail-search" style="max-width: inherit">
                    <table class="zq_search">
                        <tr>
                            <td>
                                <div class="input-group" style="width:300px">
                                    <input id="area" data-id="{$cityid}" placeholder="在这里输入城市关键词，比如曼谷" type="text" class="form-control input-sm" value="" style="font-size:12px;" v-el:area>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-white dropdown-toggle btn-sm" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button @click="search" type="button" class="btn btn-sm btn-primary">选择</button>
                            </td>
                        </tr>
                    </table>




                </div>
                <h2>
                    {$ptype_text}价格设置 - {{city.name_cn}} {{city.name_en}}
                </h2>



                <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                        <table class="zq_search">
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-primary" type="button" @click="pricenatureTroggle()">新建价格规则</button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    有效报价时间：{$begintime} 到 {$endtime}（包含当天）
                </div>
            </div>
            <div class="mail-box" style="position: relative; padding: 20px">
                <table class="table table-hover table-mail">
                    <thead>
                    <tr>
                        <th style="text-align: center">#</th>
                        <th>ID</th>
                        <th>规则</th>
                        <th>标题</th>
                        <th>优先级</th>
                        <th>价格开始时间（包含当天）</th>
                        <th>价格结束时间（包含当天）</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="read" v-for="(index,item) in items">
                        <td class="check-mail">
                            <input type="checkbox" class="i-checks" value="{{item.id}}" v-model="ids">
                        </td>

                        <td>
                            <span>{{item.id}}</span>
                        </td>

                        <td>
                            <span>{{item.nature_text}}</span>
                        </td>

                        <td>
                            <span>{{item.title}}</span>
                            <span class="label label-danger pull-right" v-if="item.cartype.length > 0">{{item.cartype}}</span>
                        </td>

                        <td>
                            <span>{{item.level}}</span>
                        </td>

                        <td>
                            <span v-if="item.nature == 0 || item.nature == 1 || item.nature == 2">--</span>
                            <span v-else>
                                {{item.begintime}}
                            </span>
                        </td>

                        <td>
                            <span v-if="item.nature == 0 || item.nature == 1 || item.nature == 2">--</span>
                            <span v-else>
                                {{item.endtime}}
                            </span>
                        </td>



                        <td>
                            <span>{{item.edittime}}</span>
                        </td>

                        <td class="">


                            <a href="{:url('edit')}/?cityid={{city.cityid}}&pricenatureid={{item.id}}&ptype={{item.ptype}}">
                                基价
                            </a>

                            <a @click="priceurgentTroggle(index)">
                                时价
                            </a>

                            <a v-if="item.nature > 0" @click="delCityPricenature(item.id)">
                                删除
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-mail" v-show="nodata">
                    <tbody>
                    <td height="250" align="center" valign="middle">
                        <h1>:( 没有数据，修改搜索条件试试!</h1>
                    </td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-urgent" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:900px">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">更新急单加价（{{currentPricenature.title}}）</h4>
            </div>

            <div class="modal-body form-horizontal">

                <div class="form-group">
                    <label class="col-sm-2 control-label">新的急单加价规则</label>
                    <div class="col-sm-2">
                        <select v-model="newUrgent.type" class="form-control select select-sm m-b" name="account" style="height: 30px; padding: 0; padding-left: 5px">
                            <option value="0" selected>车型</option>
                            <option value="1">全部</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input style="display: inline; width: 50px" type="text" v-model="newUrgent.hour" class="form-control input-sm">
                        <span style="display: inline"> 小时内，加价 </span>
                        <input style="display: inline; width: 50px" type="text" v-model="newUrgent.percent" class="form-control input-sm">
                        <span style="display: inline"> % 或者 ￥</span>
                        <input style="display: inline; width: 60px" type="text" v-model="newUrgent.price" class="form-control input-sm">
                        <span style="display: inline"> 元 </span>
                    </div>

                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-white" style="display: inline" @click="setLocalUrgent()">新增</button>
                    </div>


                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group" v-for="(index,item) in urgent" track-by="$index">

                    <div class="col-sm-12" v-if="item.price > 0">
                        &nbsp;距用车【{{item.hour}}】小时以内下单，<span v-if="item.type == 0">【车型套餐】</span><span v-else>【附加行程】</span>价格再加 ￥ {{item.price}} 元
                        - <span @click="delLocalUrgent(index)">删除</span>
                    </div>

                    <div class="col-sm-12" v-else>
                        &nbsp;距用车【{{item.hour}}】小时以内下单，<span v-if="item.type == 0">【车型套餐】</span><span v-else>【附加行程】</span>价格加价的 {{item.percent}}%
                         - <span @click="delLocalUrgent(index)">删除</span>
                    </div>


                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="setUrgent()">保存</button>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modal-pricenature" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">新增价格规则</h4>
            </div>

            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">规则标识</label>
                    <div class="col-sm-10 ">
                        <input type="text" class="input-sm form-control" v-model="newPricenature.title" placeholder="比如，端午节；如果不知道填什么，请留空，系统会自动生成" />
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">规则类型</label>
                    <div class="col-sm-10 ">
                        <select v-model="newPricenature.nature" class="form-control select select-sm m-b" style="height: 30px; padding: 0; padding-left: 10px">
                            {foreach name="natureLang" item="item" key="k"}
                                <option value="{$k}">{$item}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label">有效时间</label>
                    <div class="col-sm-10 ">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" v-model="newPricenature.begintime" />
                            <span class="input-group-addon">到</span>
                            <input type="text" class="input-sm form-control" v-model="newPricenature.endtime" />
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">优先级</label>
                    <div class="col-sm-3 ">
                        <input type="number" class="input-sm form-control" v-model="newPricenature.level" placeholder="此处填写0-9数字" />
                    </div>
                    <label class="col-sm-7 control-label" style="text-align: left">填写0-9数字，仅针对“自定义”规则类型有效</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="setCityPricenature()">保存</button>
            </div>
        </div>
    </div>
</div>


{include file="public/footer-js" /}
<script>

    Vue.filter('reverse', function (value) {
        return value.split('').reverse().join('')
    })

    Vue.filter('statusFilter', function (value) {
        switch(value)
        {
            case 0:
                return 'label-info';
            case 1:
                return 'label-danger';
            case 2:
                return 'label-danger';
            case 3:
                return 'label-danger';
            case 4:
                return 'label-danger';
            case 5:
                return 'label-danger';
            default:
                return 'label-danger';
        }
    });

    new Vue({
        el: '#wrapper',
        data: {
            items:[],
            city:{},
            ptype:{$ptype},
            newPricenature:{
                title:'',
                nature:5,
                begintime:'2016-09-09',
                endtime:'2016-09-09',
                level:0
            },
            currentPricenature:{},
            newUrgent:{
                type:1,
                hour:6,
                price:0.00,
                percent:12
            },
            urgent:[]
        },
        computed : {
           /* _urgent: function () {
                this.urgent.push(htis.newUrgent);
            }*/
        },
        methods: {
            search:function() {
                //有些非标准组件无法进行双向绑定；this.$els.area是需要在节点设置的
                var that = this;
                $.ajax( {
                    url:'{:url('getCityByCityid')}',
                    data:{
                        cityid: $(that.$els.area).attr('data-id')
                    },
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 1) {
                            that.city = data.content;
                            $.ajax({
                                url:'{:url('getCityPricenatureList')}',
                                data:{
                                    cityid: $(that.$els.area).attr('data-id'),
                                    ptype:that.ptype
                                },
                                type:'post',
                                dataType:'json',
                                success:function(data) {
                                    if(data.code == 1) {
                                        that.items = data.content;
                                    }
                                },
                                error : function() {
                                    //alert("异常！");
                                }
                            });
                        }
                    },
                    error : function() {
                        //alert("异常！");
                    }
                });
            },
            pricenatureTroggle: function() {
                $('#modal-pricenature').modal('toggle');
            },
            priceurgentTroggle: function(index) {
                $('#modal-urgent').modal('toggle');
                this.currentPricenature = this.items[index];
                var urgentJson = this.currentPricenature.urgent;
                if(urgentJson.length > 0) {
                    this.urgent = JSON.parse(urgentJson);
                    console.log(this.urgent);
                }
                else {
                    this.urgent = [];
                }
            },
            setCityPricenature: function() {
                var that = this;
                this.newPricenature.cityid = this.city.cityid;
                this.newPricenature.ptype = this.ptype;
                $.ajax({
                    url:'{:url('setCityPricenature')}',
                    data:this.newPricenature,
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        $('#modal-pricenature').modal('toggle');
                        if(data.code == 1) {
                            swal(data.msg);
                            that.search();
                        }
                        else {
                            swal('出错了 - ' + data.msg);
                        }
                    },
                    error : function() {
                        swal('出错了 - ' + data.msg);
                    }
                });
            },
            delCityPricenature: function(id) {
                var that = this;
                $.ajax({
                    url:'{:url('delCityPricenature')}',
                    data:{
                        pricenatureid: id
                    },
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 1) {
                            swal(data.msg);
                            that.search();
                        }
                    },
                    error : function() {
                        //alert("异常！");
                    }
                });
            },
            setLocalUrgent: function() {
                console.log(this.urgent);
                this.urgent.push({
                    type:this.newUrgent.type,
                    hour:this.newUrgent.hour,
                    price:this.newUrgent.price,
                    percent:this.newUrgent.percent,
                });
            },
            delLocalUrgent: function(index) {
                console.log(index);
                this.urgent.splice(index,1);
            },
            setUrgent: function() {
                var that = this;
                $.ajax({
                    url:'{:url('setUrgent')}',
                    data:{
                        pricenatureid:that.currentPricenature.id,
                        urgent:that.urgent
                    },
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        $('#modal-urgent').modal('toggle');
                        if(data.code == 1) {
                            swal(data.msg);
                            that.search();
                        }
                        else {
                            swal('出错了 - ' + data.msg);
                        }
                    },
                    error : function() {
                        swal('出错了 - ' + '未知错误');
                    }
                });
            }
        },
        ready : function () {
            this.search();
        },
    })
</script>


<!-- 日期开始 -->
<script src="{$Think.config.base_url}/static/admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    //日期
    $("#datepicker").datepicker({
        keyboardNavigation: !1,
        forceParse: !1,
        autoclose: !0,
        orientation: "top left"
    });
</script>


<script src="{$Think.config.base_url}/static/admin/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script>
    var testdataBsSuggest = $("#area").bsSuggest({
        indexId: 0,
        indexKey: 1,
        getDataMethod: "url",
        effectiveFieldsAlias: {
            cityid: "编号",
            name_la: "地区",
        },
        showHeader: true,
        data: {
            "value": [{
                "cityid": "0",
                "name_la": "lzw",
            },
            {
                "cityid": "1",
                "name_la": "lzwme",
            }],
            "defaults": "http://lzw.me"
        },
        url: "{:url('getCityListByKeyword')}" + "/?keyword=",
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