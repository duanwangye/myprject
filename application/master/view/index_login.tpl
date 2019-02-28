<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>易途吧</title>
    <link href="{$Think.config.base_url}/static/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$Think.config.base_url}/static/admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="{$Think.config.base_url}/static/admin/css/animate.min.css" rel="stylesheet">
    <link href="{$Think.config.base_url}/static/admin/css/style.min.css" rel="stylesheet">
    <link href="{$Think.config.base_url}/static/admin/css/login.min.css" rel="stylesheet">
    <link href="{$Think.config.base_url}/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1> Yitu8 </h1>
                </div>
                <div class="m-b"></div>
                <h4></h4>
            </div>
        </div>
        <div class="col-sm-5">
            <form id="form" method="post">
                <h4 class="no-margins">登录：</h4>
                <p class="m-t-md">登录到易途吧当地玩乐管理后台</p>
                <input name="username" type="text" class="form-control uname" placeholder="用户名" />
                <input name="password" type="password" class="form-control pword m-b" placeholder="密码" />
                <a href="" style="color:#ffffff;">忘记密码了？</a>
                <button type="submit" class="btn btn-primary btn-block">登录</button>
            </form>
        </div>
    </div>

</div>

<!-- 6、表单部分 -->
<script src="{$Think.config.base_url}/static/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="{$Think.config.base_url}/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="{$Think.config.base_url}/static/common/js/jquery.form.js"></script>
<script type="text/javascript">
    $(function() {
        var optionsForm = {
            beforeSubmit: function(arr, $form, options) {
                /*for (x of arr) {
                    if(x.name == 'areaid') {
                        x.value = $("#areaid").attr('data-id');
                    }
                    if(x.name == 'content') {
                        x.value = editor.html();
                    }
                    //console.log(arr);
                }*/

                //$("button[type='submit']").attr("disabled","disabled");
            },
            success: function (data) {
                if(data.code==1) {
                    window.location.href = data.forward;
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
</body>

</html>