/**
 * Created by wangyulu on 2018/5/19.
 */

var ws = new WebSocket("ws://singwa.swoole.com:8812");

ws.onopen = function (evt) {
    console.log('open succ');
};

ws.onmessage = function (evt) {
    console.log(evt.data);
    push(evt);
};

ws.onclose = function (evt) {
    console.log('close succ');
};

ws.onerror = function (evt) {
    console.log('err :');
    console.log(evt);
};

function push(evt) {
    var data = JSON.parse(evt.data);
    html = '<div class="comment">'
    html +=     '<span>' + data.user + ' </span>'
    html +=     '<span>' + data.content + '</span>'
    html +='</div>'

    $("#comments").prepend(html);
}
