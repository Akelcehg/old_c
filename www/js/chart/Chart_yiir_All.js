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
    var data3 = [];
    var canvas = document.getElementById(5)
    var ctx = canvas.getContext("2d");


    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
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
                data: data3
            }
        ]
    };



    var myLineChart = new Chart(ctx).Line(data, eval('option' + name));
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

    myLineChart.removeLastPoint = function() {
        this.scale.xLabels.pop();
        this.scale.valuesCount--;
        this.scale.fit();

        Chart.helpers.each(this.datasets,function(dataset){
            dataset.points.pop();
        },myLineChart);

        this.update();
    }


    function canvasResize(e) {
        //@TODO - make it bigger on all width

        //var width=$('#'+name).parent().width();
        // $('#'+name).css('width',width);
        //$('#'+name).css('heigth','300');
        // myLineChart.update();
    }

    function rand(min, max) { // Generate a random integer
        //
        // +   original by: Leslie Hoare

        if (max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        } else {
            return Math.floor(Math.random() * (min + 1));
        }
    }

    function onloadGenerateData() {

        get=parseGetParams()
        $.get(app.baseUrl + '/prom/rtchart/ajaxdaydataall', {

            user_type:['legal_entity','house_metering']

        },

            function (json) {

            while (myLineChart.scale.xLabels.length > 0) {
                myLineChart.removeData();

            }

            for (var i = 0; json.length > i; i++) {
                myLineChart.addData(json[i].data, json[i].label);
                prevlabel= json[i].label
            }

                    /*Chart.defaults.global.scaleOverride : true,
                    Chart.defaults.global.scaleSteps : 10,
                    Chart.defaults.global.scaleStepWidth : 50,
                    Chart.defaults.global.scaleStartValue : 0*/


                generateDataAjaxQuery()
        })




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

    function generateDataAjaxQuery(){
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
    }


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

        var link = $(e && e.tagName ? e : this),
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
    }
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





