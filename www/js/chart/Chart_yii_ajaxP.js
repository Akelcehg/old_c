$(function () {

    var chart
    var chartLegend

    var ctx


    $(document.body).on('click', '#prev',function(){
        changeMode()
    });

    $(document.body).on('click', '#next',function(){
        changeMode()
    });

    $(document.body).on('click', '#now',function(){
        changeMode()
    });

    $(document.body).on('click', '#dayMode',function(){
        changeMode()
    });

    $(document.body).on('click', '#weekMode',function(){
        changeMode()
    });

    $(document.body).on('click', '#monthMode',function(){
        changeMode()
    });

    $('a[href=#chart]').on('shown.bs.tab',function(){

        delay(function(){changeMode()},1000);

    });


    function changeMode(){

        initChartVar()
        switch (chartMode){
            case "week":
                WeekChartAndConsumption(id);
                break
            case "day":
                counterToChartByDay(id);
                break
            case "month":
                counterToTempChartByMonth(id);
                break
        }

    }

    function initChartVar(){
        chart = eval(charts[0]);
        chartLegend = eval(charts[0]+"Legend");

        ctx = eval("ctx"+name+"Consum");



    }


    function WeekChartAndConsumption(counter_id)
    {




        dateArray = [];
        for (var i=0; i <= 7; i++)
        {
            day = moment(firstDayOfWeek()).add(i,'day').format("YYYY-MM-DD");
            dateArray.push(day);
        }
        counterToChartByWeek(counter_id, dateArray);
        counterToConsumptionDetailByWeek(counter_id, dateArray);
        $('#chart').height($('#chartBody').outerHeight(true)+$('#consumDetail').outerHeight(true)+$('ul.nav-tabs').outerHeight(true)+160);

    }

     function counterToConsumptionDetailByWeek(counter_id,data) {

         $('#chartsUl').innerHTML='';

        $.post(app.baseUrl + '/prom/operations/ajaxcountertoconsumtiondetailbyweek', {
            counter_id: counter_id,
           data:data
        }, function (html) {


            $('#chartsUl').empty();
            chartLegend.innerHTML=html;

        });

    }

  /*  function counterToConsumptionDetailByDay(counter_id) {

        var link = $(e && e.tagName ? e : this),
         cell = link.closest('td'),
         row = cell.parent(),
         func = link.attr('data-func');



        date = new Date($('#today').val());
        date = date.setDate(date.getDate() - 1);
        date = new Date(date).setHours(23);
        date = new Date(date).setMinutes(59);
        date = new Date(date).setSeconds(59);
        //beginData = new Date(date).toISOString().split('T')[0];
        beginData = $('#today').val();
        endData = $('#today').val();
        get = parseGetParams();



        $.get(app.baseUrl + '/metrix/operations/ajaxcountertoconsumtiondetail', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {

            chartLegend.innerHTML=html;

        })

    }*/


    function counterToChartByWeek(counter_id, data) {


        $.post(app.baseUrl + '/prom/rtchart/ajaxweekdata', {
            counter_id: counter_id,
            data:data
        }, function (json) {


            while (chart.scale.xLabels.length > 0)
            {
                chart.removeData();
            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }

            for (var i = 0; json.length > i; i++)
            {
                chart.addData(json[i].data, json[i].label);
            }


        });


    }


     function counterToConsumptionDetailByDay(counter_id) {

        beginData = moment(moment.unix($("#date").val())).format("YYYY-MM-DD");
        endData = moment(moment.unix($("#date").val())).format("YYYY-MM-DD");
        get = parseGetParams();



        $.get(app.baseUrl + '/prom/operations/ajaxcountertoconsumtiondetail', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {
            chartLegend.innerHTML='';
            $('#chartsUl').html(html);
        })

    }

    function counterToChartByDay(counter_id) {


        beginData = moment(moment.unix($("#date").val())).format("YYYY-MM-DD");
        endData = moment(moment.unix($("#date").val())).format("YYYY-MM-DD");
        //moment(moment.unix($("#date").val())).add(1,'day').format("YYYY-MM-DD");
        get = parseGetParams();

        counterToConsumptionDetailByDay(counter_id)

        $.get(app.baseUrl + '/prom/rtchart/ajaxhourdatachart', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chart.scale.xLabels.length > 0)
            {
                chart.removeData();

            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }

            for (var i = 0; json.length > i; i++)
            {
                chart.addData(json[i].data, json[i].label);


            }

            $('#chart').height($('#chartBody').outerHeight(true)+$('#consumDetail').outerHeight(true)+$('ul.nav-tabs').outerHeight(true));

            delete json;
        })



    }

       function counterToConsumptionDetailByMonth(counter_id) {

           beginData = moment(firstDayOfMonth()).format("YYYY-MM-DD");
           endData = moment(firstDayOfMonth()).add(moment(firstDayOfMonth()).daysInMonth()-1,'day').format("YYYY-MM-DD");



        $.get(app.baseUrl + '/prom/operations/ajaxcountertoconsumtiondetail', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {
            chartLegend.innerHTML='';
            $('#chartsUl').html(html);
        })

    }

    function counterToTempChartByMonth(counter_id) {

        beginData = moment(firstDayOfMonth()).format("YYYY-MM-DD");
        endData = moment(firstDayOfMonth()).add(moment(firstDayOfMonth()).daysInMonth()-1,'day').format("YYYY-MM-DD");

        get = parseGetParams();
        counterToConsumptionDetailByMonth(counter_id)




        $.get(app.baseUrl + '/prom/rtchart/ajaxdaydata', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chart.scale.xLabels.length > 0)
            {
                chart.removeData();

            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }

            for (var i = 0; json.length > i; i++)
            {
                chart.addData(json[i].data, json[i].label);

            }
            $('#chart').height($('#chartBody').outerHeight(true)+$('#consumDetail').outerHeight(true)+$('ul.nav-tabs').outerHeight(true));
            delete json;
        })

    }




    function parseGetParams() {
        var $_GET = {};
        var __GET = window.location.search.substring(1).split("&");
        for (var i = 0; i < __GET.length; i++) {
            var getVar = __GET[i].split("=");
            $_GET[getVar[0]] = typeof (getVar[1]) == "undefined" ? "" : getVar[1];
        }
        return $_GET;
    }

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

})




