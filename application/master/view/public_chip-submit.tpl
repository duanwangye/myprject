<script src="{$Think.config.base_url}/static/common/js/jquery.form.js"></script>
<script type="text/javascript">
    $(function() {
        var options[name] = {
            beforeSubmit: function() {

            },
            success: function (data) {
                if(data.code==1) {
                    if(data.seconds!="") {
                        $.toast(data.msg);
                        setTimeout(
                                function() {
                                    window.location.href = data.forward;
                                }
                                ,data.seconds * 1000);
                    }
                    else {
                        window.location.href = data.forward;
                    }
                }
                else {
                    $.toast(data.msg, "forbidden");
                }
            }
        };
        $("#[name]").ajaxForm(options[name]);
    });
</script>