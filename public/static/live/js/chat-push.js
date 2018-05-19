/**
 * Created by wangyulu on 2018/5/19.
 */
$(function () {
    $("#msg-push").keydown(function (evt) {
        if (evt.keyCode != 13) {
            return;
        }

        var url = "http://singwa.swoole.com:8811?s=index/chat";
        var content = $(this).val();
        var data = {'content': content, 'chat_id': 1};
        $.post(url, data, function (data) {
            if (data.code != 0) {
                alert(data.msg);
            } else {
                $("#msg-push").val('');
            }
        }, 'json');
    });
});