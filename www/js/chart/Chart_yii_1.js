/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    var dataWeek=[];
    var labelWeek=[];
    var dateNow = new Date();
          if (
                 window.navigator.userAgent.match(/Macintosh/i)!=null|| 
                window.navigator.userAgent.match(/iPhone/i)!=null|| 
                window.navigator.userAgent.match(/iPad/i)!=null || 
                window.navigator.userAgent.match(/iPod/i) !=null
            )
      { 
          var dateNow=new Date(dateNow.setHours(dateNow.getHours()-dateNow.getTimezoneOffset()/60))
      }
 
    
    
    var options = {month: "numeric" ,year: "numeric"};
    var optionsW = {month: "short", day: "2-digit"};
    $('#today').val($('#endDate').val());
    init();

    //$(document.body).on('click', '#filterSubmit', counterToChart);

    for (var key in chartGlobalConfig) {
        if (chartGlobalConfig[key] != null)
        {
            Chart.defaults.global[key] = chartGlobalConfig[key];



        }
    }
    
    
    var dayCharttemp = {
        labels: [0],
        datasets: [
           
            {
                label: "Понедельник",
                fillColor: "rgba(0,0,255,0)",
                strokeColor: "rgba(0,0,255,1)",
                pointColor: "rgba(0,0,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0,0,255,1)",                                            
                data: [0]
            },          
        ]
    };

    var dayChart = {
        labels: [0],
        datasets: [
           
            {
                label: "Понедельник",
                fillColor: "rgba(255,0,0,0)",
                strokeColor: "rgba(255,0,0,1)",
                pointColor: "rgba(255,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,0,0,1)",
               
                data: [0]
            },          
        ]
    };

        var dayCharttemp = {
        labels: [0],
        datasets: [
           
            {
                label: "Понедельник",
                fillColor: "rgba(0,0,255,0.1)",
                strokeColor: "rgba(0,0,255,1)",
                pointColor: "rgba(0,0,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0,0,255,1)",                                            
                data: [0]
            },
             {
                label: "Вторник",
                fillColor: "rgba(255,165,0,0)",
                strokeColor: "rgba(255,165,0,1)",
                pointColor: "rgba(255,165,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,165,0,1)",
                data: [0]
            },
             {
                label: "Среда",
                fillColor: "rgba(255,255,0,0)",
                strokeColor: "rgba(255,255,0,1)",
                pointColor: "rgba(255,255,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,255,0,1)",
                data: [0]
            },
            {
                label: "Четверг",
                fillColor: "rgba(0,139,0,0)",
                strokeColor: "rgba(0,139,0,1)",
                pointColor: "rgba(0,139,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0,139,0,1)",
                data: [0]
            },
              {
                label: "Пятница",
                fillColor: "rgba(135,206,255,0)",
                strokeColor: "rgba(135,206,255,1)",
                pointColor: "rgba(135,206,255,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(135,206,255,1)",
                data: [0]
            },
              {
                label: "Суббота",
                fillColor: "rgba(255,0,0,0)",
                strokeColor: "rgba(255,0,0,1)",
                pointColor: "rgba(255,0,0,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,0,0,1)",
                data: [0]
            }, 
             {
                label: "Воскресенье",
                fillColor: "rgba(85,26,139,0)",
                strokeColor: "rgba(85,26,139,1)",
                pointColor: "rgba(85,26,139,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(85,26,139,1)",
                data: [0]
            }, 
            
        ]
    };

    var dayChart = {
        labels: [0],
        datasets: [
           
            {
                label: "Понедельник",
                fillColor: "rgba(255,0,0,0.1)",
                strokeColor: "rgba(255,0,0,0.5)",
                pointColor: "rgba(255,0,0,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,0,0,0.5)",
                data: [0]
            },
             {
                label: "Вторник",
                fillColor: "rgba(255,165,0,0)",
                strokeColor: "rgba(255,165,0,0.5)",
                pointColor: "rgba(255,165,0,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,165,0,0.5)",
                data: [0]
            },
             {
                label: "Среда",
                fillColor: "rgba(255,255,0,0)",
                strokeColor: "rgba(255,255,0,0.5)",
                pointColor: "rgba(255,255,0,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,255,0,0.5)",
                data: [0]
            },
            {
                label: "Четверг",
                fillColor: "rgba(0,139,0,0)",
                strokeColor: "rgba(0,139,0,0.5)",
                pointColor: "rgba(0,139,0,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0,139,0,0.5)",
                data: [0]
            },
              {
                label: "Пятница",
                fillColor: "rgba(135,206,255,0)",
                strokeColor: "rgba(135,206,255,0.5)",
                pointColor: "rgba(135,206,255,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(135,206,255,0.5)",
                data: [0]
            },
              {
                label: "Суббота",
                fillColor: "rgba(0,0,255,0)",
                strokeColor: "rgba(0,0,255,0.5)",
                pointColor: "rgba(0,0,255,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(0,0,255,0.5)",
                data: [0]
            }, 
             {
                label: "Воскресенье",
                fillColor: "rgba(85,26,139,0)",
                strokeColor: "rgba(85,26,139,0.5)",
                pointColor: "rgba(85,26,139,0.5)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(85,26,139,0.5)",
                data: [0]
            }, 
            
        ]
    };

    //var myLineChart = new Chart(ctx).Line(data,eval('option'+name));


    canvasInit();
    $('#dayChartWidget div.content').hide();
    function init() {
          $('#dayChartWidget div.content').show();
       
        $('#showing3').hide();
        $('#showing2').hide();
        $('#showing1').hide();
        $('#monthMode').hide();
        $('#weekMode').hide();
        $('#today').val($('#endDate').val());
        var date = dateNow;
        prevdate = dayLabel(new Date(date.setDate(date.getDate() - 1)));
        today = dayLabel(new Date(date.getDate()));
        $('#datelabel').html($('#endDate').val());
        $('#week').val($('#today').val());

        nextdate = dayLabel(new Date(date.setDate(date.getDate() + 1)));
        $('#nextDay').html(nextdate + '&nbsp;&nbsp;&nbsp;>>');
        $('#prevDay').html('<<&nbsp;&nbsp;&nbsp;' + prevdate);

        checkNextDay()
    }

 
    //console.log(activePoints);

    

    

    $(document.body).on('click', '#chartImageUpload', chartImageUpload);


    function chartImageUpload() {

        var canvas = document.getElementById("dayChart");
        var img = canvas.toDataURL("image/png");



        $.post(app.baseUrl + '/admin/chart/ajaxuploadimage', {
            data: 'A',
            dataB: 'B',
            image: img,
        }, function (html) {

            window.location = 'exportimage?temp=' + html;
        });
    }
    $(document.body).on('click', '#chartExcelUpload', chartExcelUpload);
    function chartExcelUpload() {
        var counter_id=$('#today').attr('counter_id');

        if ($('#dayMode:visible').length > 0)
        {
            
        date = new Date($('#today').val());
        date = date.setDate(date.getDate() - 1);

        beginData = new Date(date).toISOString().split('T')[0];

        endData = $('#today').val();
        
        $.get(app.baseUrl + '/admin/excel/ajaxchartbyday', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {
            window.location = app.baseUrl + '/admin/excel/exportexcel?title=' + html;
        });
            
            
        }
        
        if ($('#weekMode:visible').length > 0)
        {
            
            week = new Date($('#week').val());
            firstDayOfWeek = new Date(week.setDate(week.getDate() - week.getDay() + 1));

            dateArray = [];


            for (i = -1; i <= 6; i++)
                {
                    day = new Date(firstDayOfWeek);

                    day = new Date(day.setDate(day.getDate() + i)).toISOString().split('T')[0];
                    dateArray.push(day);
                }
                
                $.post(app.baseUrl + '/admin/excel/ajaxchartbyweek', {
                        counter_id: counter_id,
                        data: dateArray,
                    }, function (html) {
                        window.location = app.baseUrl + '/admin/excel/exportexcel?title=' + html;
                    });
            
        }
        
        if ($('#monthMode:visible').length > 0)
        {
            date = new Date($('#month').val());

            firstDay = new Date(date.getFullYear(), date.getMonth(), 2);
            lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 1);
            beginData = firstDay.toISOString().split('T')[0];
            endData = lastDay.toISOString().split('T')[0];
            
        $.get(app.baseUrl + '/admin/excel/ajaxchartbymonth', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {
            window.location =app.baseUrl + '/admin/excel/exportexcel?title=' + html;
        });
            
        }




    }
  
    
    
      function monthLabel(dateNow) {
              //alert(window.navigator.userAgent); 
              //alert(navigator.language);
       if (
                 window.navigator.userAgent.match(/Macintosh/i)!=null|| 
                window.navigator.userAgent.match(/iPhone/i)!=null|| 
                window.navigator.userAgent.match(/iPad/i)!=null || 
                window.navigator.userAgent.match(/iPod/i) !=null
            )
      { 
          
           return dateNow.getMonth()+1+'.'+dateNow.getFullYear();
        /*if(navigator.language == 'ru-ru')
        {
           return dateNow.toLocaleDateString().split(' ')[1]   
        }
        else
        {
          return dateNow.toLocaleDateString().split(' ')[0]
        }*/
          
      }
      else{
      return dateNow.toLocaleDateString("ru-RU", options);
  }
  }
    
    
    
    
  function dayLabel(dateNow) {
              //alert(window.navigator.userAgent);
       if (
                window.navigator.userAgent.match(/Macintosh/i)!=null|| 
                window.navigator.userAgent.match(/iPhone/i)!=null|| 
                window.navigator.userAgent.match(/iPad/i)!=null || 
                window.navigator.userAgent.match(/iPod/i) !=null
            )
      { 
          return dateNow.toISOString().split('T')[0]
      }
      else{
      return dateNow.toLocaleDateString('ru-RU', {year: "numeric"})+'-'+dateNow.toLocaleDateString('ru-RU',{month:"2-digit"})+'-'+dateNow.toLocaleDateString('ru-RU',{day:"2-digit"})
  }
  }
  
  
  function monthSlice(date)
  {
      switch (date.getMonth()) {
          
         
   case 0:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break
   case 1:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break
    case 2:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,4);
      break
    case 3:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break
    case 4:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break
    case 5:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,4);
      break
    case 6:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,4);
      break
    case 7:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break 
    case 8:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,4);
      break 
    case 9:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
     break
    case 10:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,5);
      break
    case 11:
      return date.toLocaleDateString().split(/[ /]+/)[1].slice(0,3);
      break
}
  }
  
  
  function WeekLable(week) {

        firstDayOfWeek = new Date(week.setDate(week.getDate() - week.getDay() + 1));
        lastDayOfWeek = new Date(week.setDate(week.getDate() + (7 - week.getDay())));
        
          if (
               window.navigator.userAgent.match(/Macintosh/i)!=null|| 
                window.navigator.userAgent.match(/iPhone/i)!=null|| 
                window.navigator.userAgent.match(/iPad/i)!=null || 
                window.navigator.userAgent.match(/iPod/i) !=null
            )
      { 
          return firstDayOfWeek.toLocaleDateString().split(' ')[0] +' '+monthSlice(firstDayOfWeek)+ '.  - ' + lastDayOfWeek.toLocaleDateString().split(' ')[0] +' '+monthSlice(lastDayOfWeek)+ '.';
         //return dateNow.toISOString().split('T')[0]
      }
      else { 
          return firstDayOfWeek.toLocaleDateString("ru-RU", optionsW) + ' - ' + lastDayOfWeek.toLocaleDateString("ru-RU", optionsW);
      }

    }


    //function dayLabel(dateNow) {return dateNow.getUTCFullYear()+'-'+dateNow.getUTCMonth()+'-'+dateNow.getUTCDate()}

    function checkNextDay() {

        if ($('#today').val() == dayLabel(dateNow))
        {
           
            $('#nextDay').hide();
            return false;
        }
        else
        {
            $('#nextDay').show();
            return true;
        }


    }

    function checkNextMonth() {
        if (new Date($('#month').val()).getMonth() == dateNow.getMonth())
        {
            $('#nextMonth').hide();
            return false;
        }
        else
        {
            $('#nextMonth').show();
            return true;
        }


    }



    $(document.body).on('click', '#td', DayMode);
    $(document.body).on('click', '#mt', MonthMode);
    $(document.body).on('click', '#wk', WeekMode);

    function DayMode() {
        init();
        $('#showing').html($('#td').html()+' <i class="fa fa-caret-down"></i>');
        $('#timeperiod').html('за день');



        $('#dayMode').show();
        $('#monthMode').hide();
        $('#showing3').show();
        $('#showing2').show();
        $('#showing1').show();
        counterToChartByDay($('#today').attr('counter_id'));
        counterToConsumptionDetailByDay($('#today').attr('counter_id'));
    }


    
    function WeekCalc()
    {

        currentWeek = new Date($('#week').val());
        prevWeek = new Date($('#week').val());
        nextWeek = new Date($('#week').val());

        prevWeek = new Date(prevWeek.setDate(prevWeek.getDate() - 7));
        nextWeek = new Date(nextWeek.setDate(nextWeek.getDate() + 7));



        $('#weeklabel').html(WeekLable(currentWeek));
        $('#prevWeek').html('<<&nbsp;&nbsp;&nbsp;' + WeekLable(prevWeek));
        $('#nextWeek').html(WeekLable(nextWeek) + '&nbsp;&nbsp;&nbsp;>>');

    }


    function checkNextWeek() {
        week = new Date($('#week').val());
        lastDayOfWeek = new Date(week.setDate(week.getDate() + (7 - week.getDay())));

        if (new Date(lastDayOfWeek) > new Date())
        {
            $('#nextWeek').hide();
            return false;
        }
        else
        {
            $('#nextWeek').show();
            return true;
        }


    }


    function WeekMode() {

        WeekCalc();

        $('#showing').html($('#wk').html()+' <i class="fa fa-caret-down"></i>');
        $('#dayMode').hide();
        $('#monthMode').hide();

        $('#timeperiod').html('за неделю');

        checkNextWeek();
        $('#weekMode').show();
        WeekChartAndConsumption($('#today').attr('counter_id'));
        //counterToChartByMonth($('#today').attr('counter_id'));
        //counterToConsumptionDetailByMonth($('#today').attr('counter_id'));

    }


    function prevWeekLoad() {

        prevWeek = new Date($('#week').val());
        prevWeek = new Date(prevWeek.setDate(prevWeek.getDate() - 7));
        $('#week').val(prevWeek.toISOString().split('T')[0]);

        WeekCalc();

        checkNextWeek();

        if ($('#today').attr('counter_id') != 0) {
            WeekChartAndConsumption($('#today').attr('counter_id'));
            //counterToChartByMonth($('#today').attr('counter_id'));
            //counterToConsumptionDetailByMonth($('#today').attr('counter_id'));
        }

    }

    function nextWeekLoad() {

        nextWeek = new Date($('#week').val());
        nextWeek = new Date(nextWeek.setDate(nextWeek.getDate() + 7));
        $('#week').val(nextWeek.toISOString().split('T')[0]);

        WeekCalc();

        checkNextWeek();

        if ($('#today').attr('counter_id') != 0) {
            WeekChartAndConsumption($('#today').attr('counter_id'));
            //counterToChartByMonth($('#today').attr('counter_id'));
            //counterToConsumptionDetailByMonth($('#today').attr('counter_id'));
        }

    }

    $(document.body).on('click', '#prevWeek', prevWeekLoad);
    $(document.body).on('click', '#nextWeek', nextWeekLoad);

    function WeekChartAndConsumption(counter_id)
    {
        
        week = new Date($('#week').val());
        firstDayOfWeek = new Date(week.setDate(week.getDate() - week.getDay() + 1));

        dateArray = [];
        

        for (i = -1; i <= 6; i++)
        {
            day = new Date(firstDayOfWeek);
            
            day = new Date(day.setDate(day.getDate() + i)).toISOString().split('T')[0];
            dateArray.push(day);
        }

         counterToChartByWeek(counter_id, dateArray);
         counterToConsumptionDetailByWeek(counter_id, dateArray);
    }


    function counterToChartByWeek(counter_id, data) {
      
        
       $.post(app.baseUrl + '/admin/chart/ajaxcountertochartbyweek', {
            counter_id: counter_id,
            data:data
        }, function (json) {
            
            
            while (chartdayChart.scale.xLabels.length > 0)
            {
                chartdayChart.removeData();
                $('#labeldayChart').html('');
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
                chartdayChart.addData(json[i].data, json[i].label);
                $('#counterAddress').html("-"+json[i].address);

            }
           
          
        });
        
         $.post(app.baseUrl + '/admin/chart/ajaxcountertotempchartbyweek', {
            counter_id: counter_id,
            data:data
        }, function (json) {
            
            
            while (chartdayCharttemp.scale.xLabels.length > 0)
            {
                chartdayCharttemp.removeData();
            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }
            
            tempVisibleToType(json);
            
            for (var i = 0; json.length-1 > i; i++)
            {
                chartdayCharttemp.addData(json[i].data, json[i].label);
                
            }
           
          
        });
       

    }
    
    function counterToConsumptionDetailByWeek(counter_id,data) {



        $.post(app.baseUrl + '/admin/chart/ajaxcountertoconsumtiondetailbyweek', {
            counter_id: counter_id,
           data:data
        }, function (html) {
            $('#consumptionDetail').html(html);
        });

    }







    function MonthMode() {
        $('#month').val($('#today').val());
        var dateNow = new Date($('#month').val());
        $('#showing').html($('#mt').html()+' <i class="fa fa-caret-down"></i>');

        $('#timeperiod').html('за месяц');


        $('#monthlabel').html(monthLabel(dateNow));
        $('#prevMonth').html('<<&nbsp;&nbsp;&nbsp;' + monthLabel(new Date(new Date(dateNow).setMonth(dateNow.getMonth() - 1))));
        $('#nextMonth').html(monthLabel(new Date(new Date(dateNow).setMonth(dateNow.getMonth() + 1))) + '&nbsp;&nbsp;&nbsp;>>');
        $('#dayMode').hide();
        $('#weekMode').hide();
        checkNextMonth();
        $('#monthMode').show();
        counterToChartByMonth($('#today').attr('counter_id'));
        counterToTempChartByMonth($('#today').attr('counter_id'));
        counterToConsumptionDetailByMonth($('#today').attr('counter_id'));


    }

    $(document.body).on('click', '#prevMonth', prevMonthLoad);
    $(document.body).on('click', '#nextMonth', nextMonthLoad);


    function prevMonthLoad() {

        dateprev = new Date($('#month').val());
        //newdate = new Date(dateprev.getDate()).toISOString().split('T')[0];
        newdatePML = new Date(dateprev.setMonth(dateprev.getMonth() - 1));
        prevdatePML = monthLabel( new Date(dateprev.setMonth(dateprev.getMonth() - 1)));
        nextdatePML = monthLabel(new Date(dateprev.setMonth(dateprev.getMonth() + 2)));



        $('#month').val(newdatePML.toISOString().split('T')[0]);
        $('#monthlabel').html(monthLabel(newdatePML));
        $('#nextMonth').html(nextdatePML + '&nbsp;&nbsp;&nbsp;>>');
        $('#prevMonth').html('<<&nbsp;&nbsp;&nbsp;' + prevdatePML);

        checkNextMonth();

        if ($('#today').attr('counter_id') != 0) {
            counterToChartByMonth($('#today').attr('counter_id'));
             counterToTempChartByMonth($('#today').attr('counter_id'));
            counterToConsumptionDetailByMonth($('#today').attr('counter_id'));
        }

    }

    function nextMonthLoad() {

        datenext = new Date($('#month').val());
        prevdate = monthLabel(new Date(datenext.setMonth(datenext.getMonth())));
        newdate = new Date(datenext.setMonth(datenext.getMonth() + 1));
        nextdate = monthLabel(new Date(datenext.setMonth(datenext.getMonth() + 1)));

        $('#month').val(newdate.toISOString().split('T')[0]);
        $('#monthlabel').html(monthLabel(newdate));


        checkNextMonth();
        $('#nextMonth').html(nextdate + '&nbsp;&nbsp;&nbsp;>>');
        $('#prevMonth').html('<<&nbsp;&nbsp;&nbsp;' + prevdate);
        if ($('#today').attr('counter_id') != 0) {
            counterToChartByMonth($('#today').attr('counter_id'));
             counterToTempChartByMonth($('#today').attr('counter_id'));
            counterToConsumptionDetailByMonth($('#today').attr('counter_id'));
        }
    }




    $(document.body).on('click', '#prevDay', prevDayLoad);
    $(document.body).on('click', '#nextDay', nextDayLoad);

    function prevDayLoad() {

        dateprev = new Date($('#today').val());
        //newdate = new Date(dateprev.getDate()).toISOString().split('T')[0];
        newdate = dayLabel(new Date(dateprev.setDate(dateprev.getDate() - 1)));
        prevdate = dayLabel(new Date(dateprev.setDate(dateprev.getDate() - 1)));
        nextdate = dayLabel(new Date(dateprev.setDate(dateprev.getDate() + 2)));

        $('#today').val(newdate);
        $('#datelabel').html(newdate);
        $('#nextDay').html(nextdate + '&nbsp;&nbsp;&nbsp;>>');
        $('#prevDay').html('<<&nbsp;&nbsp;&nbsp;' + prevdate);
        $('#month').val($('#today').val());
        checkNextDay();

        if ($('#today').attr('counter_id') != 0) {
            counterToChartByDay($('#today').attr('counter_id'));
            counterToConsumptionDetailByDay($('#today').attr('counter_id'));
        }

    }

    function nextDayLoad() {

        datenext = new Date($('#today').val());
        prevdate = dayLabel(new Date(datenext.setDate(datenext.getDate())));
        newdate = dayLabel(new Date(datenext.setDate(datenext.getDate() + 1)));
        nextdate = dayLabel(new Date(datenext.setDate(datenext.getDate() + 1)));

        $('#today').val(newdate);
        $('#datelabel').html(newdate);
        $('#month').val($('#today').val());

        checkNextDay();
        $('#nextDay').html(nextdate + '&nbsp;&nbsp;&nbsp;>>');
        $('#prevDay').html('<<&nbsp;&nbsp;&nbsp;' + prevdate);
        if ($('#today').attr('counter_id') != 0) {
            counterToChartByDay($('#today').attr('counter_id'));
            counterToConsumptionDetailByDay($('#today').attr('counter_id'));
        }
    }






    // var ctx = document.getElementById(name).getContext("2d");



    function canvasInit() {

        /*for (var i = 0; i < $('.chartCanvas').length; i++) {

            buf = $('.chartCanvas:eq(' + i + ')').attr('id');
            var ctx = document.getElementById(buf).getContext("2d");
            window["chart" + buf] = new Chart(ctx).Line(buf, eval('option' + buf));


        }*/
        
     
        
         var ctx = document.getElementById('dayChart').getContext("2d");
        var ctx2 = document.getElementById('dayCharttemp').getContext("2d");
        window["chart" + "dayChart"]= new Chart(ctx).Line(dayChart, eval('option' + 'dayChart'));
        window["chart" + "dayCharttemp"]= new Chart(ctx2).Line(dayCharttemp, eval('option' + 'dayCharttemp'));
        
        
    }

    //$(document.body).on('click', '.jarviswidget-toggle-btn', counterToChart);

    //$('.counter').click(function(e){counterToChart(myLineChart,e)});
    //$(document.body).on('click', '.counter', counterToChart);

    $(document.body).on('click', '.counter', graph);

    function graph(e) {
        var link = $(e && e.tagName ? e : this),
                cell = link.closest('td'),
                row = cell.parent(),
                func = link.attr('data-func'),
                counter_id = link.attr('counter_id');
        e.stopPropagation();

        $('#today').val($('#endDate').val());
        $('#datelabel').html($('#endDate').val());


        checkNextDay();



        $('#today').attr('counter_id', counter_id);
        if ($('#dayChartWidget').hasClass('jarviswidget-collapsed')) {
            $('#dayChartWidget a.jarviswidget-toggle-btn').trigger('click');
        }

        DayMode();

    }


    function counterToConsumptionDetailByDay(counter_id) {

        /*var link = $(e && e.tagName ? e : this),
         cell = link.closest('td'),
         row = cell.parent(),
         func = link.attr('data-func');*/



        date = new Date($('#today').val());
        date = date.setDate(date.getDate() - 1);
        date = new Date(date).setHours(23);
        date = new Date(date).setMinutes(59);
        date = new Date(date).setSeconds(59);
        //beginData = new Date(date).toISOString().split('T')[0];
        beginData = $('#today').val();
        endData = $('#today').val();
        get = parseGetParams();



        $.get(app.baseUrl + '/admin/chart/ajaxcountertoconsumtiondetail', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
        }, function (html) {
            $('#consumptionDetail').html(html);
        })

    }


    /*$(document.body).on('click', '.counter', counterToChartByDay);
     $(document.body).on('click', '.counter', counterToChartByWeek);
     $(document.body).on('click', '.counter', counterToChartByMonth);*/

    function counterToChartByDay(counter_id) {

        /*var link = $(e && e.tagName ? e : this),
         cell = link.closest('td'),
         row = cell.parent(),
         func = link.attr('data-func');*/



        date = new Date($('#today').val());
        date = date.setDate(date.getDate() - 1);

        beginData = new Date(date).toISOString().split('T')[0];

        endData = $('#today').val();
        get = parseGetParams();



        $.get(app.baseUrl + '/admin/chart/ajaxcountertochartbyday', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chartdayChart.scale.xLabels.length > 0)
            {
                chartdayChart.removeData();
                $('#labeldayChart').html('');
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
                chartdayChart.addData(json[i].data, json[i].label);
                $('#counterAddress').html("-"+json[i].address);

            }


            //$('#labeldayChart').html(json[0].address);


            delete json;
        })
        
        $.get(app.baseUrl + '/admin/chart/ajaxcountertemptochartbyday', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chartdayCharttemp.scale.xLabels.length > 0)
            {
                chartdayCharttemp.removeData();
            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }

            tempVisibleToType(json);
              

            for (var i = 0; json.length-1 > i; i++)
            {
                if(json[i].type=="discrete")
                {
                   $('#tmp').hide(); 
                   $('#no-data').hide();
                }
                
                if(json[i].type=="built-in")
                {
                   $('#tmp').show(); 
                }
                
                chartdayCharttemp.addData(json[i].data, json[i].label);
                
            }
            delete json;
        })

    }

    function tempVisibleToType(json)
    {
                if(json[json.length-1].type=="built-in")
                {
                   $('#temp').show(); 
                }
                else
                {
                   $('#temp').hide();
                    $('#no-data').hide();
                }
    }

    function counterToConsumptionDetailByMonth(counter_id) {

        /*var link = $(e && e.tagName ? e : this),
         cell = link.closest('td'),
         row = cell.parent(),
         func = link.attr('data-func');*/



        date1 = new Date($('#month').val());

        firstDay1 = new Date(date.getFullYear(), date.getMonth(), 2);
        lastDay1 = new Date(date.getFullYear(), date.getMonth() + 1, 1);
        beginData1 = firstDay1.toISOString().split('T')[0];
        endData1 = lastDay1.toISOString().split('T')[0];



        $.get(app.baseUrl + '/admin/chart/ajaxcountertoconsumtiondetail', {
            counter_id: counter_id,
            beginDate: beginData1,
            endDate: endData1,
        }, function (html) {
          $('#consumptionDetail').html(html);
        })

    }


    function counterToTempChartByMonth(counter_id) {

        date = new Date($('#month').val());

        firstDay = new Date(date.getFullYear(), date.getMonth(), 2);
        lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 1);
        beginData = firstDay.toISOString().split('T')[0];
        endData = lastDay.toISOString().split('T')[0];
        get = parseGetParams();



        $.get(app.baseUrl + '/admin/chart/ajaxcountertotempchart', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chartdayCharttemp.scale.xLabels.length > 0)
            {
                chartdayCharttemp.removeData();
            }

            if (json.length < 2)
            {
                $('#no-data').show();
            }
            else
            {
                $('#no-data').hide();
            }

             tempVisibleToType(json);
            
            for (var i = 0; json.length-1 > i; i++)
            {
                chartdayCharttemp.addData(json[i].data, json[i].label);

            }
           
        })

    }


    function counterToChartByMonth(counter_id) {

        date = new Date($('#month').val());

        firstDay = new Date(date.getFullYear(), date.getMonth(), 2);
        lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 1);
        beginData = firstDay.toISOString().split('T')[0];
        endData = lastDay.toISOString().split('T')[0];
        get = parseGetParams();



        $.get(app.baseUrl + '/admin/chart/ajaxcountertochart', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type: get['type']
        }, function (json) {

            while (chartdayChart.scale.xLabels.length > 0)
            {
                chartdayChart.removeData();
                $('#labeldayChart').html('');
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
                chartdayChart.addData(json[i].data, json[i].label);

            }
            $('#counterAddress').html("-"+json[1].address);
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


    //function canvasResize(e) {
    //@TODO - make it bigger on all width

    //var width=$('#'+name).parent().width();
    // $('#'+name).css('width',width);
    //$('#'+name).css('heigth','300');
    // myLineChart.update();
    //}


    /*   function counterToChart(e) {
     
     var link = $(e && e.tagName ? e : this),
     cell = link.closest('td'),
     row = cell.parent(),
     func = link.attr('data-func');
     
     counter_id = link.attr('counter_id');
     beginData = $('#beginDate').val();
     endData = $('#endDate').val();
     get = parseGetParams();
     
     ;
     while (myLineChart.scale.xLabels.length > 0)
     {
     myLineChart.removeData();
     }
     
     $.get(app.baseUrl + '/admin/chart/ajaxcountertochart', {
     counter_id: counter_id,
     beginDate: beginData,
     endDate: endData,
     type: get['type']
     }, function (json) {
     
     for (var i = 0; json.length > i; i++)
     {
     myLineChart.addData(json[i].data, json[i].label);
     
     }
     $('#label' + name).html(json[1].address);
     
     })
     
     }
     });*/


});
