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
                <h2>
                    订单列表 （第{{page.begin}}页 / {{page.count}}条 共）
                </h2>
                <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                        <table class="zq_search">
                            <tr>
                                <td>
                                    <div class="input-group" style="width:200px">
                                        <input id="user" data-id="0" type="text" class="form-control input-sm" value="" style="font-size:12px;" v-el:user>
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
                                    <div class="input-group" style="width:200px">
                                        <input id="area" data-id="0" type="text" class="form-control input-sm" value="地区不限" style="font-size:12px;" v-el:area>
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
                                    <select id="ptype" data-placeholder="选择类别" class="chosen-select" style="width:150px;" v-model="cn.ptype" v-el:ptype>
                                        <option value="0" hassubinfo="true" selected>所有分类</option>
                                        {foreach name="ptype" item="item" key="k"}
                                            <option value="{$k}" hassubinfo="true" >{$item}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <div class="input-daterange input-group" datepicker="datepicker">
                                        <input v-model="cn.begintime" type="text" class="input-sm form-control" name="starttime" value="" style="width:100px"/>
                                        <span class="input-group-addon">到</span>
                                        <input v-model="cn.endtime" type="text" class="input-sm form-control" name="endtime" value="" style="width:100px"/>
                                    </div>

                                <td>
                                <button @click="search" type="button" class="btn btn-sm btn-primary">搜索</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="刷新"><i class="fa fa-refresh"></i> 刷新</button>
                    <button @click="status" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="展示">展示
                    </button>
                    <button @click="level" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="推荐">推荐
                    </button>
                    <button @click="del" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="删除">删除
                    </button>
                    <button class="btn btn-white btn-sm" @click="page(-1)"><i class="fa fa-arrow-left"></i>
                    </button>
                    <button class="btn btn-white btn-sm" @click="page(1)"><i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <div class="mail-box" style="position: relative;">

                <table class="table table-hover table-mail">
                    <tbody>
                    <tr class="read" v-for="item in items">
                        <td class="check-mail">
                            <input type="checkbox" class="i-checks" value="{{item.id}}" v-model="ids">
                        </td>

                        <td>
                            <span>{{item.ordernumber}}</span>
                        </td>

                        <td>
                            <span>{{item.partner_name}}</span>
                        </td>

                        <td>
                            <a>{{item.areatext}}</a>
                            <span class="label {{item.order_status | statusFilter}} pull-right" style="margin-left:5px;">{{item._order_status}}</span>
                            <span class="label label-warning pull-right">{{item.statustext}}</span>
                        </td>


                        <td class="mail-subject">
                            <a target="_blank" href="{:url('index/product/detail')}/?id={{item.pid}}">[{{item.ptypetext}}]</a>
                            <a target="_blank" href="{:url('index/product/detail')}/?id={{item.pid}}">{{item.title}}</a>
                            <span v-if="item.ptype == 1"><span style="color:#1ab394">{{item.from_name}}</span> -> {{item.to_name}}</span>
                            <span v-if="item.ptype == 2">{{item.from_name}} -> <span style="color:#1ab394">{{item.to_name}}</span></span>
                        </td>
                        <td class="">
                            <a href="{:url('detail')}/?id={{item.id}}">[详细]</a>
                        </td>
                        <td class="text-right mail-date"> {{item.addtime}}</td>
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
            items: [],
            page: {
                begin:1,
                count:10,
                amout:0
            },
            ids :[],
            cn: {
                uid:0,
                areaid:1,
                typeid:2,
                begintime:'',
                endtime:'',
            },
            load : false,
        },
        methods: {
            status: function() {
                if(this.ids.length == 0) {
                    swal({
                        title: '哎呀',
                        text: '没有选择任何条目',
                    })
                    return
                }


                var that = this;
                var reverse = Vue.filter('reverse');
                var status = [];
                for(item of that.items) {
                    for (var i = 0; i < this.ids.length; i++) {
                        if (this.ids[i] == item.id) {
                            status.push(item.status);
                        }
                    }
                }
                //console.log(status);

                $.ajax({
                    url: '{:url('status')}',
                    data: {
                        cn:this.cnCreate(),
                        id:reverse(that.ids),
                        p:this.pc.p,
                        status:reverse(status),
                    },
                    success: function(data) {
                        that.items = data.content;
                        swal({
                            title: data.msg,
                            type: "success"
                        },function () {
                            //window.location.href = data.forward;
                            that.ids = [];
                        })
                    }
                });
            },
            level: function() {
                if(this.ids.length == 0) {
                    swal({
                        title: '哎呀',
                        text: '没有选择任何条目',
                    })
                    return
                }


                var that = this;
                var reverse = Vue.filter('reverse');
                var level = [];
                for(item of that.items) {
                    for (var i = 0; i < this.ids.length; i++) {
                        if (this.ids[i] == item.id) {
                            level.push(item.level);
                        }
                    }
                }
                //console.log(status);

                $.ajax({
                    url: '{:url('level')}',
                    data: {
                        cn:this.cnCreate(),
                        id:reverse(that.ids),
                        p:this.pc.p,
                        level:reverse(level),
                    },
                    success: function(data) {
                        that.items = data.content;
                        swal({
                            title: data.msg,
                            type: "success"
                        },function () {
                            //window.location.href = data.forward;
                            that.ids = [];
                        })
                    }
                });
            },
            del: function () {
                if(this.ids.length == 0) {
                    swal({
                        title: '哎呀',
                        text: '没有选择任何条目',
                    })
                    return
                }
                var that = this;
                var reverse = Vue.filter('reverse');
                swal({
                    title: "您确定要删除这条信息吗",
                    text: "删除后将无法恢复，请谨慎操作！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                },
                function() {
                    $.ajax({
                        url: '{:url('del')}',
                        data: {
                            cn:that.cnCreate(),
                            p:that.pc.p,
                            id:reverse(that.ids),
                        },
                        success: function(data) {
                            that.items = data.content;
                            swal({
                                title: data.msg,
                                type: "success"
                            },function () {
                                //window.location.href = data.forward;
                                that.ids = [];
                            })
                        }
                    });
                })
            },
            page:function(c) {
                if(this.pc.p == 0 && c < 0) {
                    return;
                }
                this.pc.p += c * this.pc.c;

                var that = this;
                $.ajax({
                    url: '{:url('index')}',
                    data: {
                        cn:this.cnCreate(),
                        p:this.pc.p,
                    },
                    success: function(data) {
                        if(data.code == 1) {
                            if(data.content.length == 0) {
                                swal({
                                    title: '哎呀',
                                    text: '没有数据了',
                                })
                                that.pc.p -= that.pc.c;
                                return;
                            }
                            that.items = data.content;
                        }
                    }
                });
            },
            search:function() {
                //有些非标准组件无法进行双向绑定；this.$els.area是需要在节点设置的
                var that = this;
                $.ajax({
                    url: '{:url('index')}',
                    data: {
                        cn:this.cnCreate(),
                        p:this.pc.p,
                    },
                    success: function(data) {
                        if(data.code == 1) {
                            that.items = data.content;
                        }
                    },
                    beforeSend : function() {
                        that.load = true;
                    },
                    complete : function() {
                        that.load = false;
                    }
                });
            },
            cnCreate:function() {
                this.cn.uid = $(this.$els.user).attr('data-id');
                this.cn.areaid = $(this.$els.area).attr('data-id');
                this.cn.typeid = $(this.$els.type).val();
                var json = Vue.filter('json');
                return json.read(this.cn);
            }
        },
        ready : function () {
            var that = this;
            $.ajax({
                url:'{:url('getList')}',// 跳转到 action
                data:{page : that.page},
                type:'post',
                dataType:'json',
                success:function(data) {
                    console.log(data);
                    if(data.code == 1) {
                        that.items = data.content.order_list;
                        that.page = data.content.page;
                    }
                },
                error : function() {
                    alert("异常！");
                }
            });
        },
        computed : {
            nodata:function(){
                return this.items.length == 0 ? true : false;
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
            id: "编号",
            keyword: "地区",
        },
        showHeader: true,
        data: {
            "value": [{
                "id": "0",
                "keyword": "lzw",
            },
                {
                    "id": "1",
                    "keyword": "lzwme",
                },
                {
                    "id": "2",
                    "keyword": "meizu",
                },
                {
                    "id": "3",
                    "keyword": "flyme",
                }],
            "defaults": "http://lzw.me"
        },
        url: "{:url('getCityListByKeyword')}" + "/keyword/",
        processData: function(json) {
            console.log(json);
            var i, len, data = {
                value: []
            };

            if (json.code!=1) {
                return;
            }
            len = json.content.length;
            data.value.push({
                "id": 0,
                "keyword": "地区不限",
            })
            for (i = 0; i < len; i++) {
                data.value.push({
                    "id": json.content[i][0],
                    "keyword": json.content[i][1],
                })
            }
            return data;
        }
    });



    var phonedataBsSuggest = $("#user").bsSuggest({
        indexId: 0,
        indexKey: 2,
        getDataMethod: "url",
        effectiveFieldsAlias: {
            id: "编号",
            truename: "姓名",
            keyword: "手机号",
        },
        showHeader: true,
        data: {
            "value": [{
                "id": "0",
                "truename": "",
                "keyword": "lzw",
            },
                {
                    "id": "1",
                    "truename": "",
                    "keyword": "lzwme",
                },
                {
                    "id": "2",
                    "truename": "",
                    "keyword": "meizu",
                },
                {
                    "id": "3",
                    "truename": "",
                    "keyword": "flyme",
                }],
            "defaults": "http://lzw.me"
        },
        url: "{:url('order/getUser')}" + "/keyword/",
        processData: function(json) {
            var i, len, data = {
                value: []
            };

            if (json.code!=1) {
                return;
            }
            len = json.content.length;
            data.value.push({
                "id": 0,
                "truename": "姓名不限",
                "keyword": "手机不限",
            })
            for (i = 0; i < len; i++) {
                data.value.push({
                    "id": json.content[i]['u_id'],
                    "truename": json.content[i]['u_name'],
                    "keyword": json.content[i]['u_phone'],
                })
            }
            return data;
        }
    });


</script>
<style>
    .chosen-container-single .chosen-single { padding-top: 3px;padding-bottom: 0;padding-left:10px;}
    .chosen-container-single .chosen-single div b {  background-position: 0px 4px;  }
    .chosen-container-active.chosen-with-drop .chosen-single div b {  background-position-y:4px;  }
</style>


<script src="{$Think.config.base_url}/static/admin/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="{$Think.config.base_url}/static/admin/js/plugins/chosen/chosen.jquery.js"></script>
<script>
    //日期
    $("div[datepicker='datepicker']").datepicker({
        keyboardNavigation: !1,
        forceParse: !1,
        autoclose: !0
    });


    $("#type").chosen({
        no_results_text: "没有找到！",
        allow_single_deselect: !0
    });
</script>
{include file="public/footer" /}