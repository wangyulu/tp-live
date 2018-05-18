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
};

ws.onerror = function (evt) {
    console.log(evt);
};

ws.onclose = function (evt) {
    console.log(evt);
};