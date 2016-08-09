/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {


    $(document.body).on('click', '.excel-export', exportExcel);

    $(document.body).on('click', '#sform', getConsumpMonth);






    $('a[href=#calendarWidget]').on('shown.bs.tab',function(event, ui) {
        $('#calendar').fullCalendar('render');

        counterToCalendar();
    })

    $('a[href=#optionsTab]').on('shown.bs.tab',function(event, ui) {
        $('#calendar').fullCalendar('render');

        counterToCalendar();
    })

    $('#calendar').fullCalendar({
        lang: 'ru',
        eventLimit: true, // If you set a number it will hide the itens
        eventLimitText: "Something"
    });

    $('#calendar').fullCalendar('render');
    counterToCalendar();



    function counterToCalendar() {

        get = parseGetParams();

        $('#calendar').fullCalendar('removeEvents');

        $.get(app.baseUrl + '/counter/operations/ajaxcountertocalendar', {
            counter_id: get['id'],
        }, function (json) {

            $('#calendar').fullCalendar('addEventSource', {events: json});
            $('#calendar').fullCalendar( 'refetchEvents' );

        });
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
        type =link.attr('type');


        $.get(app.baseUrl+'/admin/export/ajaxlistcounterbyexcel', {
            geo_location_id: data,
            beginDate: beginData ,
            endDate: endData ,
            type:type,

        }, function (htmlData) {



            window.location=app.baseUrl + '/admin/export/exportexcel?title='+htmlData;

        });

    }


    function getConsumpMonth(e) {

        get = parseGetParams();
        datec=moment($('#year').val()+"-"+$('#month').val(),"YY-MM")

        $.get(app.baseUrl+'/counter/counter/ajaxconsumpmonth', {

            id:get['id'],
            beginDate:moment(datec).set('date',1).format("YYYY-MM-DD"),
            endDate:moment(datec).set('date',moment(datec).daysInMonth()).format("YYYY-MM-DD"),
            year:$('#year').val(),
            month:$('#month').val()

        }, function (htmlData) {

            $('#monthConsumTable').replaceWith(htmlData);

        });

    }



    function getHref(e){

        var link = $(e && e.tagName ? e : this);



        window.location.hash=link.attr("href");

    }



    function setActiveTab(e){

        var s=parseHash()

        if(s != "undefined"){
            alert(s);
        }

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

    function parseHash() {

        var __GET = window.location.search.substring(1).split("#");

        return __GET[1];
    }


});