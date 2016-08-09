/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    
    $(document.body).on('click', '#filterSubmit', counterToChart);
    for (var key in chartGlobalConfig) {
        if (chartGlobalConfig[key]!=null)
        {
           Chart.defaults.global[key]=chartGlobalConfig[key];
            
            
            
        }
    }
    
    

    
    var ctx = document.getElementById(name).getContext("2d");
    var ctx2 = document.getElementById(name+'temp').getContext("2d");

    var data = {
        labels: [0],
        datasets: [
            {
                label: "My Second dataset",
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(151,187,205,1)",
                data: [0]
            }
        ]
    };

    var myLineChart = new Chart(ctx).Line(data,eval('option'+name));
    var myLineChart2 = new Chart(ctx2).Line(data,eval('option'+name));
    
   $(document.body).on('click', '.jarviswidget-toggle-btn', counterToChart);
    
    $('.counter').click(function(e){counterToChart(myLineChart,e)});
   $(document.body).on('click', '.counter', counterToChart);
   
   
     
   
   $(window).resize(function() {canvasResize()});

  
    function canvasResize(e) {
        //@TODO - make it bigger on all width

        //var width=$('#'+name).parent().width();
       // $('#'+name).css('width',width);
        //$('#'+name).css('heigth','300');
        // myLineChart.update();
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
  
      
        if(!$('#consumptionChart').hasClass('jarviswidget-collapsed')){
        $.get(app.baseUrl + '/admin/counter/ajaxcountertochart', {
            counter_id: counter_id,
            beginDate: beginData,
            endDate: endData,
            type:get['type']
        }, function (json) {
           
            while(myLineChart.scale.xLabels.length>0)
            {
                     myLineChart.removeData();
                 }
           
           for(var i=0;json.length>i;i++)
            { 
            myLineChart.addData(json[i].data, json[i].label);
           
            }
            $('#label'+name).html(json[1].address);
          
        })
delete json;
    }}
});
function parseGetParams() { 
   var $_GET = {}; 
   var __GET = window.location.search.substring(1).split("&"); 
   for(var i=0; i<__GET.length; i++) { 
      var getVar = __GET[i].split("="); 
      $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
   } 
   return $_GET; 
} 



