
/**
 * Created by alexey on 20.05.16.
 */

$(function () {

    $(document.body).on('click', '#closeValve', closeValve);

    $(document.body).on('click', '#openValve', openValve);

    $(document.body).on('click', '.excel-export', exportExcel);


    $('a[href=#calendarWidget]').on('shown.bs.tab',function(event, ui) {
        $('#calendar').fullCalendar('render');

        metrixtoCalendar();
    })

    $('a[href=#optionsTab]').on('shown.bs.tab',function(event, ui) {
        $('#calendar').fullCalendar('render');

        metrixtoCalendar();
    })

    /*

    $('#calendar').fullCalendar({
        lang: 'ru',
        eventLimit: true, // If you set a number it will hide the itens
        eventLimitText: "Something"
    });

    $('#calendar').fullCalendar('render');
    metrixtoCalendar();*/
    refreshValve();


});
var t;
function metrixtoCalendar() {


        get = parseGetParams();

        $('#calendar').fullCalendar('removeEvents');

        $.get(app.baseUrl + '/metrix/operations/ajaxmetrixtocalendar', {
            counter_id: get['id'],
        }, function (json) {

            $('#calendar').fullCalendar('addEventSource', {events: json});
            $('#calendar').fullCalendar( 'refetchEvents' );

        });

}

function closeValve() {
   // clearTimeout(t);

    $.get(app.baseUrl+'/metrix/operations/close', {
        id:$(this).attr('counter_id'),
    }, function (htmlData) {

        $('#MetrixValveButton').replaceWith(htmlData);
        refreshValve()

    });

}

function openValve() {

    clearTimeout(t);

    $.get(app.baseUrl+'/metrix/operations/open', {
        id:$(this).attr('counter_id'),
    }, function (htmlData) {

        $('#MetrixValveButton').replaceWith(htmlData);
        refreshValve()
    });

}

function refreshValve() {

    get=parseGetParams()

    $.get(app.baseUrl+'/metrix/operations/refresh', {
        lang:get['lang'],
        id:get['id'],
    }, function (htmlData) {

        $('#MetrixValveButton').replaceWith(htmlData);

    });
     t=setTimeout(function(){refreshValve()},5000);
}

function exportExcel(e) {

    e.stopPropagation();

    var link = $(e && e.tagName ? e : this),
        cell = link.closest('td'),
        row = cell.parent(),
        func = link.attr('data-func');
    data = link.attr('geo_location_id');
    beginData =link.attr('beginDate')+" 00:00:00";
    endData =link.attr('endDate')+" 00:00:00";
  //  type =link.attr('type');


    $.get(app.baseUrl+'/metrix/export/ajaxlistcounterbyexcel', {
        geo_location_id: data,
        beginDate: beginData ,
        endDate: endData ,

    }, function (htmlData) {

        window.location=app.baseUrl + '/metrix/export/exportexcel?title='+htmlData;

    });

}

function parseGetParams() {
    var $_GET = {};
    var __GET = window.location.search.substring(1).split("&");
    for(var i=0; i<__GET.length; i++) {
        var getVar = __GET[i].split("=");
        $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1];
    }
    return $_GET;
}