var callbackFunction = function (data) {
    //console.log(data);
    var info = data.query.results.channel.item.condition;
    $('#wxIcon2').append('<img src="http://l.yimg.com/a/i/us/we/52/' + info.code + '.gif" width="34" height="34" title="' + info.text + '" />');
    $('#wxTemp').html(info.temp + '&deg;' + (u.toUpperCase()));
};
$(function () {
    var query = "SELECT * FROM weather.forecast WHERE location='" + loc + "' AND u='" + u + "'";
    var cacheBuster = Math.floor((new Date().getTime()) / 1200 / 1000);
    var url = 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent(query) + '&format=json&_nocache=' + cacheBuster;
    $.ajax({
        url: url,
        dataType: 'jsonp',
        cache: true,
        jsonpCallback: 'callbackFunction'
    });

});