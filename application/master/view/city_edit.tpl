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
                                <button onclick="window.location.href='{:url('index', ['cityid'=>$cityid, 'ptype'=>$ptype])}'" type="button" class="btn btn-sm btn-primary">返回 价格规则列表</button>
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
                                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="保存" @click="update()">保存</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <span v-if="pricenature.nature == 0">
                        报价规则：{{pricenature.nature_text}} - 有效时间（永久）
                    </span>
                    <span v-if="pricenature.nature == 1">
                        报价规则：{{pricenature.nature_text}} - 有效时间（周六和周天）
                    </span>
                    <span v-if="pricenature.nature == 2">
                        报价规则：{{pricenature.nature_text}} - 有效时间（周天）
                    </span>
                    <span v-if="pricenature.nature > 2">
                        报价规则：{{pricenature.nature_text}} - {{pricenature.title}} - 有效时间（{{pricenature.begintime}} - {{pricenature.endtime}} 包含当天）
                    </span>
                </div>
            </div>
            <div class="mail-box" style="position: relative; padding: 20px">
                <div class="row">
                    <div class="col-sm-3 b-r">
                        <h3 class="m-t-none m-b">经济型</h3>
                        <div class="form-horizontal">
                            <div class="hr-line-dashed"></div>

                            <div class="form-group" v-for="(index,item) in cartypeList1">
                                <label class="col-sm-4 control-label">{{item.name}}</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b"><span class="input-group-addon">¥</span>
                                        <input type="text" class="form-control" v-model="item.price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 b-r">
                        <h3 class="m-t-none m-b">舒适性</h3>
                        <div class="form-horizontal">
                            <div class="hr-line-dashed"></div>
                            <div class="form-group" v-for="(index,item) in cartypeList2">
                                <label class="col-sm-4 control-label">{{item.name}}</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b"><span class="input-group-addon">¥</span>
                                        <input type="text" class="form-control" v-model="item.price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 b-r">
                        <h3 class="m-t-none m-b">豪华型</h3>
                        <div class="form-horizontal">
                            <div class="hr-line-dashed"></div>

                            <div class="form-group" v-for="(index,item) in cartypeList3">
                                <label class="col-sm-4 control-label">{{item.name}}</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b"><span class="input-group-addon">¥</span>
                                        <input type="text" class="form-control" v-model="item.price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h3 class="m-t-none m-b color1">价格系数</h3>
                        <div class="hr-line-dashed"></div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">市区套餐里程</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <input type="text" class="form-control" v-model="addroute.free_distance">
                                        <span class="input-group-addon">米</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">郊区套餐里程</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <input type="text" class="form-control" v-model="addroute.free_distance1">
                                        <span class="input-group-addon">米</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">套餐时长</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <input type="text" class="form-control" v-model="addroute.free_time">
                                        <span class="input-group-addon">分</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">超出套餐里程费用</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon">￥</span>
                                        <input type="text" class="form-control" v-model="addroute.over_distance_priceper">
                                        <span class="input-group-addon">元/公里</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">超出套餐时长费用</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon">￥</span>
                                        <input type="text" class="form-control" v-model="addroute.over_time_priceper">
                                        <span class="input-group-addon">元/15分钟</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">夜间服务费</label>
                                <div class="col-sm-8">
                                    <div class="input-group m-b">
                                        <span class="input-group-addon">￥</span>
                                        <input type="text" class="form-control" v-model="addroute.night">
                                        <span class="input-group-addon">元</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        el: '#vue',
        data: {
            cartypeList: [],
            addroute:{},
            city:{},
            pricenatureid: {$pricenatureid},
            ptype:{$ptype},
            pricenature:{},
            nature:{
                0:true,
                1:true,
                2:true,
                3:true,
                4:true,
                5:true
            },
            naturekey:0
        },
        methods: {
            search:function() {
                //有些非标准组件无法进行双向绑定；this.$els.area是需要在节点设置的
                var that = this;
                $.ajax( {
                    url:'{:url('getCityByCityid')}',
                    data:{
                        cityid: {$cityid}
                    },
                    type:'post',
                    dataType:'json',
                    success:function(data) {
                        if(data.code == 1) {
                            that.city = data.content;
                            $.ajax({
                                url:'{:url('getCartypeList')}',
                                data:{
                                    cityid: that.city.cityid,
                                    pricenatureid:that.pricenatureid,
                                    ptype:that.ptype
                                },
                                type:'post',
                                dataType:'json',
                                success:function(data) {
                                    if(data.code == 1) {
                                        that.cartypeList = data.content;
                                    }
                                },
                                error : function() {
                                    //alert("异常！");
                                }
                            });
                            $.ajax({
                                url:'{:url('getAddroute')}',
                                data:{
                                    cityid: that.city.cityid,
                                    pricenatureid:that.pricenatureid,
                                    ptype:that.ptype
                                },
                                type:'post',
                                dataType:'json',
                                success:function(data) {
                                    if(data.code == 1) {
                                        that.addroute = data.content;
                                    }
                                },
                                error : function() {
                                    //alert("异常！");
                                }
                            });

                            $.ajax({
                                url:'{:url('getCityPricenature')}',
                                data:{
                                    pricenatureid:that.pricenatureid,
                                },
                                type:'post',
                                dataType:'json',
                                success:function(data) {
                                    if(data.code == 1) {
                                        that.pricenature = data.content;
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
            update:function() {
                var that = this;
                $.ajax({
                    url: '{:url('setPriceByCartypeAndAddroute')}',
                    data: {
                        cartype_list: this.cartypeList,
                        addroute: this.addroute,
                        cityid:this.city.cityid,
                        ptype:that.ptype,
                        pricenatureid:that.pricenatureid
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        that.search();
                        swal(data.msg);
                    },
                    error : function() {
                        //alert("异常！");
                    }
                });
            },
            natureEvent:function(nature) {
                this.search();
            }
        },
        ready : function () {
            this.search();
        },
        computed : {
            cartypeList1:function(){
                var _item = [];
                for(var item of this.cartypeList) {
                    if(item.type == 1) {
                        _item.push(item);
                    }
                }
                return _item;
            },
            cartypeList2:function(){
                var _item = [];
                for(var item of this.cartypeList) {
                    if(item.type == 2) {
                        _item.push(item);
                    }
                }
                return _item;
            },
            cartypeList3:function(){
                var _item = [];
                for(var item of this.cartypeList) {
                    if(item.type == 3) {
                        _item.push(item);
                    }
                }
                return _item;
            }
        }
    })
</script>

<script src="{$Think.config.base_url}/static/admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    //日期
    $("div[datepicker='datepicker']").datepicker({
        keyboardNavigation: !1,
        forceParse: !1,
        autoclose: !0
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