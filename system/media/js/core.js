var Core = {};
var Cookie = {};
var Opacity = {};
Core.POST = function(data,url,status,htm){
    $("#"+status).html("<img src='/system_media/images/ajax-loader.gif'></img>");
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        error: function(req, text, error) {
            $("#"+status).addClass('warning');
            $("#"+status).html('Произошла непредвиденная ошибка повторите позже!');
            Core.Opacity(status,1);
        },
        success: function (data) {
            $("#"+status).removeClass('success');
            $("#"+status).removeClass('fail');
            $("#"+status).removeClass('warning');
            if(data[0])
                $("#"+status).addClass('success');
            else
                $("#"+status).addClass('fail');
            if(data[0]=='html'){
                $("#"+htm).replaceWith(data[1]);
                $("#"+status).html(data[2]);
            }else
                $("#"+status).html(data[1]);
            Core.Opacity(status,0.03);
        },
        dataType: 'json'
    });
},
Core.XmlLoad = function(block,url){
    $("#"+block).html("<center><img src='/system_media/images/ajax-loader.gif'></img></center>");
    $.ajax({
        url: url,
        dataType: "html",
        success: function(xml) {
            $("#"+block).html(xml);
            Core.Opacity(block,0.006);
        }
    });
},
Core.Opacity = function(block,speed){
    $("#"+block).css("opacity",0);
    Opacity[block] = setInterval(function(){
        if($("#"+block).css("opacity")>=1)
            clearInterval(Opacity[block]);
        else
            $("#"+block).css("opacity",parseFloat($("#"+block).css("opacity"))+speed);
    },1);
},
Core.Show = function(hu){
    if($(hu).css('display')=='none'){
        $(hu).show(100);
    }else{
        $(hu).hide(100);
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

