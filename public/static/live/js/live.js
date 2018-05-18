/**
 * Created by wangyulu on 2018/5/18.
 */
var ws = new WebSocket('ws://singwa.swoole.com:8811');

ws.onopen = function (evt) {
    console.log(evt);
    console.log('client open succ');
};

ws.onmessage = function (evt) {
    console.log(evt.data);
    push(evt);
};

ws.onerror = function (evt) {
    console.log(evt);
};

ws.onclose = function (evt) {
    console.log(evt);
};

function push(evt) {
    console.log(evt.data);
    var data = JSON.parse(evt.data);
    console.log(data);

    html =  '<div class="frame">'
    html +=     '<h3 class="frame-header">'
    html +=         '<i class="icon iconfont icon-shijian"></i>第' + data.type + '节 01：40'
    html +=     '</h3>'
    html +=     '<div class="frame-item">'
    html +=         '<span class="frame-dot"></span>'
    html +=         '<div class="frame-item-author">'
    html +=             '<img src="' + data.logo + '" width="20px" height="20px" /> ' + data.title
    html +=         '</div>'
    if (data.content.length > 0) {
        var content = data.content.replace(/\r\n/g, "<br/>");
        html +=     '<p>' + content + '</p>'
    }
    if (data.image.length > 0) {
        html +=     '<p>'
        html +=         '<img src="' + data.image + '" width="40%" />'
        html +=     '</p>'
    }
    html +=     '</div>'
    html += '</div>';

    $("#match-result").prepend(html);
}