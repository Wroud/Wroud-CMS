var Core = {};
var Cookie = {};
var Show = {};
Core.POST = function(data,url,status){
    $("#"+status).html("<img src='/system_media/images/ajax-loader.gif'>");
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        error: function(req, text, error) {
            $("#"+status).addClass('warning');
            $("#"+status).html('Произошла непредвиденная ошибка повторите позже!');
        },
        success: function (data) {
            if(data[0])
                $("#"+status).addClass('success');
            else
                $("#"+status).addClass('fail');
            $("#"+status).html(data[1]);
        },
        dataType: 'json'
    });
},
Core.Show = function(hu){
    if(Show[hu]){
        $(hu).hide(100);
        Show[hu]=false;
    }else{
        $(hu).show(100);
        Show[hu]=true;
    }
},
Core.Circle = function(cont,title,data,radi){
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: cont,
            defaultSeriesType: 'line',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: title
        },
        tooltip: {
            pointFormat: 'Игроков: {point.y}<br>{series.name}: <b>{point.percentage}</b>%',
            percentageDecimals: 1
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                size:radi,
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: 'Процент',
            data: data
        }]
    });
}

Cookie.read = function(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)
            if (end == -1)
                end = cookie.length;
            setStr = unescape(cookie.substring(offset, end));
        }
    }
    return(setStr);
},
Cookie.create = function(name, value, args) {
    document.cookie = name + "=" + 
    ((args['escape']) ? escape(value) : value) +
    ((args['expires']) ? "; expires=" + args['expires'] : "") +
    ((args['path']) ? "; path=" + args['path'] : "") +
    ((args['domain']) ? "; domain=" + args['domain'] : "") +
    ((args['secure']) ? "; secure" : "");
}

