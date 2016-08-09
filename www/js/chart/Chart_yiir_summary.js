/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {

    $(document.body).on('click', '#filterSubmit', counterToChart);
    for (var key in chartGlobalConfig) {
        if (chartGlobalConfig[key] != null) {
            Chart.defaults.global[key] = chartGlobalConfig[key];


        }
    }

      var prevVal=0;
    var prevlabel=0;

    function TimeLabel(minus) {
        var tm = new Date();

        tm = new Date(tm.setSeconds(tm.getSeconds() - minus))
        var h = tm.getHours();
        var m = tm.getMinutes();
        var s = tm.getSeconds()
        m = checkTime(m);
        s = checkTime(s);
        h = checkTime(h);
        return h + ":" + m + ":" + s;

    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    function getTime60() {
        a = [];
        for (i = 60; i > 0; i--) {
            a.push(TimeLabel(i))
        }
        return a;
    }

    function getConsumption() {
        a = [];
        for (i = 60; i > 0; i--) {
            a.push(indArray[rand(0, 89)])


        }
        return a;
    }

    var labels = [];
    var labels2 = [];
    var labels3 = [];
    var labels4 = [];

    var canvas1 = document.getElementById(1)
    var ctx1 = canvas1.getContext("2d");

    var canvas2 = document.getElementById(2)
    var ctx2 = canvas2.getContext("2d");

    var canvas3 = document.getElementById(3)
    var ctx3 = canvas3.getContext("2d");

    var canvas4 = document.getElementById(4)
    var ctx4 = canvas4.getContext("2d");




    var gradient = ctx1.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(87,136,156,0)");
    gradient.addColorStop(1, "rgba(87,136,156,1)");


    var data = {
        labels: labels,
        datasets: [
            {
                label: "My Second dataset",
                fillColor: gradient,
                strokeColor: "rgba(87,136,156,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(87,136,156,1)",
                data: []
    }
        ]
    };

    var data2 = {
        labels: labels2,
        datasets: [
            {
                label: "My Second dataset",
                fillColor: gradient,
                strokeColor: "rgba(87,136,156,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(87,136,156,1)",
                data: []
            }
        ]
    };

    var data5 = {
        labels: labels3,
        datasets: [
            {
                label: "My Second dataset",
                fillColor: gradient,
                strokeColor: "rgba(87,136,156,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(87,136,156,1)",
                data: []
            }
        ]
    };

    var data4 = {
        labels: labels4,
        datasets: [
            {
                label: "My Second dataset",
                fillColor: gradient,
                strokeColor: "rgba(87,136,156,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(87,136,156,1)",
                data: []
            }
        ]
    };



    var myLineChart1 = new Chart(ctx1).Line(data, eval('option' + name));

    var myLineChart2 = new Chart(ctx2).Line(data2, eval('option' + name));

    var myLineChart3 = new Chart(ctx3).Line(data5, eval('option' + name));

    var myLineChart4 = new Chart(ctx4).Line(data4, eval('option' + name));


    onloadGenerateData()

    getMonth()

    $(document.body).on('click', '#onoffswitch',function(){$('#onoffswitch').toggleClass('rtOn'); generateData();});

    $(document.body).on('click', '.jarviswidget-toggle-btn', counterToChart);

    $('.counter').click(function (e) {
        counterToChart(myLineChart, e)
    });
    $(document.body).on('click', '.counter', counterToChart);


    $(window).resize(function () {
        canvasResize()
    });

    /*myLineChart.removeLastPoint = function() {
        this.scale.xLabels.pop();
        this.scale.valuesCount--;
        this.scale.fit();

        Chart.helpers.each(this.datasets,function(dataset){
            dataset.points.pop();
        },myLineChart);

        this.update();
    }*/


    function canvasResize(e) {
        //@TODO - make it bigger on all width

        //var width=$('#'+name).parent().width();
        // $('#'+name).css('width',width);
        //$('#'+name).css('heigth','300');
        // myLineChart.update();
    }


    function onloadGenerateData1() {
        $.get(app.baseUrl + '/prom/rtchart/ajaxdaydata', {
                type:'grs'
            },

            function (json1) {



                for (var i1 = 0; json1.length > i1; i1++) {
                    myLineChart1.addData(json1[i1].data, json1[i1].label);
                   // prevlabel= json1[i1].label
                }
                onloadGenerateData2();
            })
    }

    function onloadGenerateData2() {

        $.get(app.baseUrl + '/prom/rtchart/ajaxdaydata', {
                type:'prom'
            },

            function (json2) {


                for (var i2 = 0; json2.length > i2; i2++) {
                    myLineChart2.addData(json2[i2].data, json2[i2].label);
                   // prevlabel= json2[i2].label
                }
                onloadGenerateData3();
            })
    }

    function onloadGenerateData3() {


        $.get(app.baseUrl + '/admin/chart/ajaxcountertochart', {


                user_type:'house_metering'
            },

            function (json3) {



                for (var i3 = 0; json3.length > i3; i3++) {
                    myLineChart3.addData(json3[i3].data, json3[i3].label);
                    //prevlabel= json3[i3].label
                }
                onloadGenerateData4();
            })
    }
    function onloadGenerateData4() {

        $.get(app.baseUrl + '/admin/chart/ajaxcountertochart', {


                user_type:'legal_entity'
            },

            function (json4) {



                for (var i4 = 0; json4.length > i4; i4++) {
                    myLineChart4.addData(json4[i4].data, json4[i4].label);
                   // prevlabel= json4[i4].label
                }


            })

    }

    function onloadGenerateData() {

        onloadGenerateData1();

    }
    function getMonth(){
        /*canvas.height=canvas.height+20
        height=$(canvas).height()
         width=$(canvas).width()
        $(canvas).attr('style','height:'+canvas.height+'px;width'+canvas.width+'px')
        canvas.height=canvas.height+20
        $.get(app.baseUrl + '/prom/rtchart/ajaxmonth', {},

            function (json) {

                widthLabel=0

                for (var i = 0; json.length > i; i++) {

                    ctx.font = Chart.defaults.global.labelFontStyle + " " + Chart.defaults.global.labelFontSize+"px " + Chart.defaults.global.labelFontFamily;
                    ctx.fillStyle = 'black';
                    ctx.textBaseline = 'middle';
                    text = moment(json[i].label + "-01")

                    widthText= width*(json[i].width)

                  //  ctx.fillText(text.format('MMM'), widthLabel+widthText/2 ,height+10 , 200);
                    widthLabel= (width-widthText)/2+widthText-20;
                }

            })*/
    }

  /*  function generateDataAjaxQuery(){
        get=parseGetParams()
        $.get(app.baseUrl + '/prom/rtchart/ajaxcheck', {type:get['type']}, function (json) {

            if (prevVal!=json.data) {
                if(prevlabel==json.label){
                    myLineChart.removeLastPoint()}
                else{
                    prevlabel=json.label
                }
                myLineChart.addData(json.data, json.label);

            }
        });
    }*/


    function generateData(e) {

        generateDataAjaxQuery()

        if($('#onoffswitch').hasClass('rtOn')) {

           var t = setTimeout(function(){generateData()}, 5000);
        }else{
            clearTimeout(t);
        }

        setTimeout(function(){


            killGenerateDate()}, 3600000);
    }

    function killGenerateDate() {

        clearTimeout(t);

    }


    function counterToChart(e) {

       /* var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
            row = cell.parent(),
            func = link.attr('data-func');

        counter_id = link.attr('counter_id');
        beginData = $('#beginDate').val();
        endData = $('#endDate').val();
        get = parseGetParams();


        if (!$('#consumptionChart').hasClass('jarviswidget-collapsed')) {
            $.get(app.baseUrl + '/admin/counter/ajaxcountertochart', {
                counter_id: counter_id,
                beginDate: beginData,
                endDate: endData,
                type: get['type']
            }, function (json) {

                while (myLineChart.scale.xLabels.length > 0) {
                    myLineChart.removeData();
                }

                for (var i = 0; json.length > i; i++) {
                    myLineChart.addData(json[i].data, json[i].label);

                }
                $('#label' + name).html(json[1].address);

            })
            delete json;
        }
   */ }
});
function parseGetParams() {
    var $_GET = {};
    var __GET = window.location.search.substring(1).split("&");
    for (var i = 0; i < __GET.length; i++) {
        var getVar = __GET[i].split("=");
        $_GET[getVar[0]] = typeof(getVar[1]) == "undefined" ? "" : getVar[1];
    }
    return $_GET;
}





