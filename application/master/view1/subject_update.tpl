{include file="public/header-begin" /}
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/webuploader/webuploader.css">
<link rel="stylesheet" href="{$Think.config.resources}/css/demo/webuploader-demo.min.css">
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/datapicker/datepicker3.css">
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/chosen/chosen.css">
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/summernote/summernote.css">
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/summernote/summernote-bs3.css" >
<link rel="stylesheet" href="{$Think.config.resources}/css/plugins/sweetalert/sweetalert.css">
<script src="/static/common/js/vue.js"></script>
{include file="public/header-end" /}
<div class="wrapper wrapper-content">
    <form id="form" method="post" class="form-horizontal">
    <div class="row">
        <div class="col-sm-6">
            <div class="ibox float-e-margins no-drop">
                <div class="ibox-title">
                    <h5>产品简介</h5>
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

                        <div class="form-group">
                            <label class="col-sm-2 control-label">标的标题</label>

                            <div class="col-sm-10">
                                {present name="model.package"}
                                <input name="id" type="hidden" class="form-control" value="{$model.id|default='0'}">
                                {/present}
                                <input name="title" type="text" class="form-control" value="{$model.title|default=''}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类别</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                   <select id="typeid" name="typeid[]" data-placeholder="选择类别" multiple class="chosen-select" style="width:200px;">
                                        {foreach name="type" item="item"}
                                        <option value="{$item.id}" hassubinfo="true" {in name="item.id" value="$model.typeid|default='1'"}selected{/in}>{$item.title}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">地区</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input name="areaid" type="hidden" value="">
                                    <input name="areatext" data-id="{$model.areaid|default=''}" id="areaid" type="text" class="form-control" value="{$model.areatext|default=''}">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">目的地</label>

                            <div class="col-sm-10">
                                <input name="address" id="address" type="text" class="form-control" value="{$model.address|default=''}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">库存</label>
                            <div class="col-sm-10">
                                <input name="ku" id="ku" type="text" class="form-control" value="{$model.ku|default='1000'}">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-2 control-label">特色</label>

                            <div class="col-sm-10">
                                <textarea name="introduce" id="introduce" class="form-control" style="height: 100px;">{$model.introduce|default=''}</textarea>
                                <span class="help-block m-b-none">不要超过200字</span>
                            </div>

                        </div>
                </div>
            </div>

            <div class="ibox float-e-margins no-drop">
                <div class="ibox-title">
                    <h5>商品标题图片</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_file_upload.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_file_upload.html#">选项1</a>
                            </li>
                            <li><a href="form_file_upload.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="page-container">
                        <p>支持文件拖拽、QQ截屏工具，或者点击添加图片按钮。</p>
                        <div id="uploader" class="wu-example">
                            <div class="queueList">
                                <div id="dndArea" class="placeholder">
                                    <div id="filePicker"></div>
                                    <p>或将照片拖到这里，单次最多可选3张</p>
                                </div>
                            </div>
                            <div class="statusBar" style="display:none;">
                                <div class="progress">
                                    <span class="text">0%</span>
                                    <span class="percentage"></span>
                                </div>
                                <div class="info"></div>
                                <div class="btns">
                                    <div id="filePicker2"></div>
                                    <div class="uploadBtn">开始上传</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="thumb">

                    </div>
                </div>
            </div>


        </div>
        <div class="col-sm-6" id="vue">
                <div class="ibox float-e-margins" group="package">
                    <div class="ibox-title">
                        <h5>套餐</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li onclick="clonePackage(this)"><a>:)</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content" style="text-align: center;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>标题</th>
                                <th>儿童价</th>
                                <th>成人价</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in items">
                                <td align="left"><input name="tid[]" type="hidden" v-model="item.id">
                                    <span v-bind:class="{ 'badge badge-danger': item.id==0, 'badge badge-primary': item.id >0}">{{item.id}}</span>
                                </td>
                                <td align="left"><input name="ttitle[]" type="hidden" v-model="item.title">{{item.title}}</td>
                                <td align="left"><input name="price[]" type="hidden" v-model="item.price">￥ {{item.price | priceFilter 2}}</td>
                                <td align="left"><input name="price2[]" type="hidden" v-model="item.price2">￥ {{item.price2 | priceFilter 2}}</td>
                                <td align="left">
                                    <a @click="updateShow($index)">[改]</a>
                                    <a @click="updateDel($index, item.id)">[删]</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <a data-toggle="modal" href="#modal-package" class="btn btn-primary btn-lg" @click="updateShow(-1)">新增一个套餐</a>
                    </div>
                </div>

                <div class="ibox float-e-margins" group="package">
                    <div class="ibox-title">
                        <h5>费用包含</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li onclick="clonePackage(this)"><a>:)</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content" style="text-align: center;">
                        <textarea name="contain" id="contain" style="width:100%;height:100px;">{$model.content.contain|default=''}</textarea>
                    </div>
                </div>

                <div class="ibox float-e-margins" group="package">
                <div class="ibox-title">
                    <h5>费用不包含</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li onclick="clonePackage(this)"><a>:)</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="text-align: center;">
                    <textarea name="ucontain" id="ucontain" style="width:100%;height:100px;">{$model.content.ucontain|default=''}</textarea>
                </div>
            </div>

                <div class="ibox float-e-margins" group="package">
                <div class="ibox-title">
                    <h5>预定须知</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li onclick="clonePackage(this)"><a>:)</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="text-align: center;">
                    <textarea name="notice" id="notice" style="width:100%;height:100px;">{$model.content.notice|default=''}</textarea>
                </div>
            </div>

                <div class="ibox float-e-margins" group="package">
                <div class="ibox-title">
                    <h5>退赔规则</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li onclick="clonePackage(this)"><a>:)</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="text-align: center;">
                    <textarea name="restitution" id="restitution" style="width:100%;height:100px;">{$model.content.restitution|default=''}</textarea>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins no-drop">
                <div class="ibox-title">
                    <h5>产品详细</h5>
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
                    <textarea name="content" id="content" style="width:100%;height:300px;">{$model.content.content|default=''}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins no-drop">
                <div class="ibox-content">
                    <p style="text-align:center;">
                        <button type="submit" class="btn btn-primary btn-lg">保存全部</button>
                        <button type="button" class="btn btn-default btn-lg">返回</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>





{include file="public/footer-js" /}
<script src="{$Think.config.base_url}/js/jquery-ui-1.10.4.min.js"></script>
<script src="{$Think.config.base_url}/js/content.min.js?v=1.0.0"></script>
<script src="{$Think.config.base_url}/js/plugins/iCheck/icheck.min.js"></script>
<script>

    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>

<script type="text/javascript">
    var BASE_URL = '{$Think.config.base_url}/js/plugins/webuploader';
    var upyun_api_url = '{:url('/admin/upload/upurl/count/10')}';
    var callbackUploadSuccess = function(json, booturl) {
        $("<input>",
                {
                    name:'thumb[]',
                    type:'hidden',
                    val:booturl + json.url
                }
        ).appendTo("#thumb");
    }
</script>
<script src="{$Think.config.resources}/js/plugins/webuploader/webuploader.min.js"></script>
<script src="{$Think.config.resources}/js/demo/webuploader-demo.js"></script>




<script src="{$Think.config.resources}/js/plugins/chosen/chosen.jquery.js"></script>
<script>


    $("#typeid").chosen({
        no_results_text: "没有找到！",
        allow_single_deselect: !0
    });
</script>

<!-- 4、地区部分 -->
<script src="{$Think.config.resources}/js/plugins/suggest/bootstrap-suggest.min.js"></script>
<script>

    var testdataBsSuggest = $("#areaid").bsSuggest({
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
        url: "{:url('product/getPos')}" + "/keyword/",
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
                    "id": json.content[i][0],
                    "keyword": json.content[i][1],
                })
            }
            return data;
        }
    });
</script>


<!-- 3、编辑器部分 -->
<script charset="utf-8" src="{$Think.config.resources}/plugins/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="{$Think.config.resources}/plugins/kindeditor/lang/zh-CN.js"></script>
<script>
    KindEditor.ready(function(K) {
        window.editorContent = K.create('#content');
    });

    KindEditor.ready(function(K) {
        window.editorContain = K.create('#contain', {
            items: [
                'source', 'forecolor', 'hilitecolor', 'bold', 'template',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat','fullscreen'
            ]
        });
    });

    KindEditor.ready(function(K) {
        window.editorUcontain = K.create('#ucontain', {
            items: [
                'source', 'forecolor', 'hilitecolor', 'bold', 'template',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat','fullscreen'
            ]
        });
    });

    KindEditor.ready(function(K) {
        window.editorNotice = K.create('#notice', {
            items: [
                'source', 'forecolor', 'hilitecolor', 'bold', 'template',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat','fullscreen'
            ]
        });
    });

    KindEditor.ready(function(K) {
        window.editorRestitution = K.create('#restitution', {
            items: [
                'source', 'forecolor', 'hilitecolor', 'bold', 'template',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat','fullscreen'
            ]
        });
    });
</script>




<!-- 4、package部分 -->

<script>
    /*var package = $("div[group='package']");
    //package.clone(true).find("input[name='tid[]']").remove();
    package.find('ul li').click(function() {
        package.after(package.clone(true));
    });*/
    function clonePackage(that) {
        //console.log($(that).parents("div[group='package']"));
        var parent = $(that).parents("div[group='package']");
        var newPackage = parent.clone(true);
        newPackage.find("input[name='tid[]']").remove();
        $(parent).after(newPackage);
    }
</script>

<!-- 5、拖动部分 -->
<script>
    $(document).ready(function(){WinMove()});
</script>

<!-- 6、表单部分 -->
<script src="{$Think.config.resources}/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="{$Think.config.resources}/static/common/js/jquery.form.js"></script>
<script type="text/javascript">
    $(function() {
        var optionsForm = {
            beforeSubmit: function(arr, $form, options) {
                for (x of arr) {
                    if(x.name == 'areaid') {
                        x.value = $("#areaid").attr('data-id');
                    }
                    if(x.name == 'content') {
                        x.value = editorContent.html();
                    }
                    if(x.name == 'contain') {
                        x.value = editorContain.html();
                    }
                    if(x.name == 'ucontain') {
                        x.value = editorUcontain.html();
                    }
                    if(x.name == 'notice') {
                        x.value = editorNotice.html();
                    }

                    if(x.name == 'restitution') {
                        x.value = editorRestitution.html();
                    }
                    //console.log(arr);
                }

                //$("button[type='submit']").attr("disabled","disabled");
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
                    $("button[type='submit']").removeAttr("disabled");
                }
            }
        };
        $("#form").ajaxForm(optionsForm);
    });
</script>


<!-- 7.套餐部分 -->
<!--
<script src="{$Think.config.resources}/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<script>
    //日期
    $("div[datepicker='datepicker']").datepicker({
        keyboardNavigation: !1,
        forceParse: !1,
        autoclose: !0
    });
</script>
-->

<div class="modal inmodal" id="modal-package" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">更新套餐</h4>
            </div>

            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">套餐标题</label>
                    <div class="col-sm-8">
                        <input name="tid[]" class="form-control" type="hidden" v-model="item.id">
                        <input name="ttitle[]" class="form-control" type="text" value="" v-model="item.title">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">成人价/儿童价</label>
                    <div class="col-sm-4">
                        <div class="input-group"><span class="input-group-addon">¥</span>
                            <input name="price[]" type="text" class="form-control" value="" v-model="item.price"> <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group"><span class="input-group-addon">¥</span>
                            <input name="price2[]" type="text" class="form-control" value="" v-model="item.price2"> <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                </div>
                <!--
                    <div class="form-group">
                        <label class="col-sm-3 control-label">报价有效</label>
                        <div class="col-sm-8">
                            <div class="input-daterange input-group" datepicker="datepicker">
                                <input type="text" class="input-sm form-control" name="starttime[]" value="{$item.starttime}" />
                                <span class="input-group-addon">到</span>
                                <input type="text" class="input-sm form-control" name="endtime[]" value="{$item.endtime}" />
                            </div>
                        </div>
                    </div>
                    -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" @click="updateAct()">保存</button>
            </div>
        </div>
    </div>
</div>

<script>

    Vue.filter('priceFilter', function (value, num) {
        return parseFloat(value).toFixed(num);
    })

    new Vue({
        el: 'body',
        data: {
            items: [
                {present name="model.package"}
                {volist name="model.package" id="item"}
                {
                    id:{$item.id},
                    title:"{$item.title}",
                    price:{$item.price},
                    price2:{$item.price2},
                },
                {/volist}
                {/present}
            ],
            item : {
                id : 0,
                title : "",
                price : "",
                price2 : "",
            },
            package: -1,//package是新增还是更新，-1是新增，大于-1的话是更新
            load : false,
        },
        methods: {
            updateShow: function(index) {
                var priceFilter = Vue.filter('priceFilter');
                if(index == -1) {
                    this.item.id = 0;
                    this.item.title = "";
                    this.item.price = "";
                    this.item.price2 = "";
                    this.package = index;
                    return;
                }
                var item = this.items[index];
                this.item.title = item.title;
                this.item.price = priceFilter(item.price, 0);
                this.item.price2 = priceFilter(item.price2, 0);
                this.package = index;
                $('#modal-package').modal('toggle');
            },
            updateAct: function() {
                var priceFilter = Vue.filter('priceFilter');
                if(this.package == -1) {
                    this.items.push({
                        id:0,
                        title:this.item.title,
                        price:priceFilter(this.item.price, 2),
                        price2:priceFilter(this.item.price2, 2),
                    });
                    $('#modal-package').modal('toggle');
                    return;
                }
                var item = this.items[this.package];
                item.title = this.item.title;
                item.price = priceFilter(this.item.price, 2),
                item.price2 = priceFilter(this.item.price2, 2),

                $('#modal-package').modal('toggle');
            },
            updateDel: function(index, id) {
                swal({
                    title: "您确定要删除这条信息吗",
                    text: "删除后将无法恢复，请谨慎操作！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    $.getJSON('{:url('packageDel')}' + '/?id=' + id, function(data) {
                        if(data.code == 1) {
                            that.items.splice(index, 1);
                            swal("删除成功！", "您已经永久删除了这条信息。", "success");
                            return;
                        }
                        swal("删除失败！", "不知道啥原因", "error");
                    });
                });


                var that = this;

            }
        },
        ready : function () {
            /*var that = this;
            $.getJSON('{:url('index')}',function(data) {
                if(data.code == 1) {
                    that.items = data.content;
                }
            });*/
        },
        computed : {
            /*nodata:function(){
                return this.items.length == 0 ? true : false;
            }*/
        }
    })
</script>
{include file="public/footer" /}