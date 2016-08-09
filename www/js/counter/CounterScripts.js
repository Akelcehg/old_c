/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
 
    $(document.body).on('click', '#reset', reset);

    $(document.body).on('click', '#filterSubmit', submitFilter);
    $(document.body).on('click', '#allCounterFilterSubmit', submitFilterAllCounters);
    $(document.body).on('click', '#filterSubmitGraph', submitFilterGraph);
    $(document.body).on('click', '.drillDown', drillDown);
    $(document.body).on('keyup','input.form-control',enterFilter);
    $(document.body).on('blur', 'input.form-control', filterPjax);
    $(document.body).on('click', 'tr.sort th a',sort);
    $(document.body).on('click', 'tr.sort th a',function(){return false;});

    //$(document.body).on('click', '.btn',alertsFilterSwitch);
    $(document.body).on('click', '#consumptionShow',consumptionShow);
    $(document.body).on('click', '.alertsDrillDown', alertsDrillDown);
    $(document.body).on('click', '.alertsDrillDownModem', alertsDrillDownModem);
    $(document.body).on('click', '#ChartAndMapToggleButton', toggleChartAndMap);
    $(document.body).on('change', '#region', selectRegion); 
    //$(document.body).on('change', '#city', selectCity);
    $(document.body).on('click', '.ajax-pager', ajaxPagination);
    $(document.body).on('click', '.table tbody tr',checkTR);


    $(document.body).on('click', '#exportCounter', counterExport);
    $(document.body).on('click', '#exportCounter1C', counterExport1C);
    $(document.body).on('click', '.button-column', exportExcel);
    $(document.body).on('click', '#exportExcel', exportExcel);

    $(document).on('ready',startTime);







    function getReports(e) {

        get = parseGetParams();

        $.get(app.baseUrl+'/prom/correctors/ajaxreport', {

            id:get['id'],
            year:$('#year').val(),
            month:$('#month').val(),

        }, function (htmlData) {

           $('#browse-day-reports-grid').replaceWith(htmlData);
            $('#notif-day').show
            dt = new Date(year, month, 1);
            $('#notif-day span').html(dt.toLocaleDateString({month:'long'}));


        });

    }

    function getReportsMonth(e) {

        get = parseGetParams();

        $.get(app.baseUrl+'/prom/correctors/ajaxreportmonth', {

            id:get['id'],
            year:$('#yeary').val(),


        }, function (htmlData) {

            $('#browse-month-reports-grid').replaceWith(htmlData);
            $('#notif-month').show
            $('#notif-month span').html("20"+year+" год");


        });

    }



    function checkTR(e){
      
      $('.table tbody tr').removeClass('selectedTR');
      
      $('selectedTR').removeClass('selectedTR');
      $( this).not('.appended-row').addClass('selectedTR');
      e.stopPropagation();
        };
  
            
          
    
    $(document.body).on('click', '.remove-appended-row', function () {
        
        var appendedRow = $(this).closest('.appended-row'),
                func = appendedRow.attr('data-func'),
                row = appendedRow.prevAll('.selected-' + func + ':first'),
                link = row.find('[data-func="' + func + '"]');

        appendedRow.remove();
        link.closest('td').removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length);
         row.nextAll('.loading-row').remove();
        return false;
    });
    
    
    
   
    


function enterFilter(e){
    if(e.which==13) 
    { 
        var link = $(e && e.tagName ? e : this),
            cell = link.closest('div').parent(),
            row = link,
            func = link.attr('data-func');
            data = link.attr('geo_location_id');
            beginData = $('#beginDate').val();
            endData = $('#endDate').val();
            get = parseGetParams();
    //alert(cell.attr('geo_location_id'));
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
    // state of selected row
    if (row.hasClass('selected-' + func)) {

        row.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func + ':first').remove();


        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

             }                                 
       
           $.get(app.baseUrl+'/admin/counter/ajaxlistcounter', {
            flat:$('input[name="UserCountersSearch[flat]"]').val(),
            user_modem_id:$('input[name="UserCountersSearch[user_modem_id]"]').val(),
            serial_number:$('input[name="UserCountersSearch[serial_number]"]').val(),
            real_serial_number:$('input[name="UserCountersSearch[real_serial_number]"]').val(),
            updated_at:$('input[name="UserCountersSearch[updated_at]"]').val(),
            fulladdress:$('input[name="UserCountersSearch[fulladdress]"]').val(),
            geo_location_id:cell.attr('geo_location_id'),
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            sort:get['sort'],
           leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
        }, function (htmlData) {
         
          
          $('#browse-flatCounter-grid').parent().html(htmlData);
          $('#browse-flatCounter-grid'+cell.attr('geo_location_id')).parent().html(htmlData);
           
        });
       
    }
}
    
    
function consumptionShow(e) {

    e.stopPropagation();

    var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
            row = cell.parent(),
            func = link.attr('data-func');
    data = link.attr('geo_location_id');
    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
  
        $.get(app.baseUrl+'/admin/counter/houseindicationsforperiod', {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
        }, function (htmlData) {
               
             link.parent().html(htmlData);
             
         
        });
    
}

 function alertsFilterSwitch(e) {
    
  
    
    if($('#'+$(this).attr('for')).prop('checked')){
        $('#'+$(this).attr('for')).prop('checked',false);
    }
    else{
        $('#'+$(this).attr('for')).prop('checked',true);
    }
     
    
            $(this).toggleClass('active');
            //$(this).parent().find('span').toggleClass('greyFont');
            //$(this).parent().find('span').toggleClass('greyFont');
            submitFilter(e);
}




function alertsDrillDown(e) {
      var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
             func = link.attr('data-func');
            alerts_type = link.attr('alerts_type');
            row = $('#logitSuda');
            beginData = $('#beginDate').val();
            endData = $('#endDate').val();
            get = parseGetParams();
            
    // state of selected row
    if (row.hasClass('selected-' + func)) {

        cell.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func + ':first').remove();

    } else {

        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

        // remove all loading content
        row.nextAll('.loading-row').remove();

        // let add new one
        row.after('<tr class="loading-row"><td colspan="' + spanColumn + '">' + getLoadingIndicator() + '</td></tr>');

        // toggle state
        row.addClass('selected selected-' + func)
                .children('.active-cell').removeClass('loaded');

        cell.addClass('active-cell loaded');

        

        $.get(app.baseUrl+'/admin/counter/ajaxlistcounteralerts', {
            data: eval(alerts_type+'A'),
            alerts_type:alerts_type,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type']
        }, function (htmlData) {

            // remove all loading content
            row.nextAll('.loading-row').remove();

            // insert new one
            row
                    .after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                            '<td colspan="' + spanColumn + '">' +
                            htmlData +
                            '</td>' +
                            '</tr>');

            //set as loaded
            row.children('.active-cell').addClass('loaded');
        });
    }
}

function alertsDrillDownModem(e) {
      var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
             func = link.attr('data-func');
            alerts_type = link.attr('alerts_type');
            row = $('#logitSuda');
            beginData = $('#beginDate').val();
            endData = $('#endDate').val();
            get = parseGetParams();
            
    // state of selected row
   
    if (row.hasClass('selected-' + func)) {
    

        cell.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func ).remove();

    } else { 

        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

        // remove all loading content
        row.nextAll('.loading-row').remove();

        // let add new one
        row.after('<tr class="loading-row"><td colspan="' + spanColumn + '">' + getLoadingIndicator() + '</td></tr>');

        // toggle state
        row.addClass('selected selected-' + func)
                .children('.active-cell').removeClass('loaded');

        cell.addClass('active-cell loaded');

        

            
        $.get(app.baseUrl+'/admin/modem/ajaxlistcounteralertsmodems', {
            data: eval(alerts_type+'ModemA'),
            alerts_type:alerts_type,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type']
        }, function (htmlData) {

            // remove all loading content
            row.nextAll('.loading-row').remove();

            // insert new one
            row
                    .after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                            '<td colspan="' + spanColumn + '">' +'Модемы:'+
                            htmlData +
                            '</td>' +
                            '</tr>');

            //set as loaded
            row.children('.active-cell').addClass('loaded');
        });
        
        if(eval(alerts_type+'A').length!=0){
             
          $.get(app.baseUrl+'/admin/counter/ajaxlistcounteralerts', {
            data: eval(alerts_type+'A'),
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type']
        }, function (htmlData) {

            // remove all loading content
            row.nextAll('.loading-row').remove();

            // insert new one
            row
                    .after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                            '<td colspan="' + spanColumn + '">' +'Счетчики:'+
                            htmlData +
                            '</td>' +
                            '</tr>');

            //set as loaded
            row.children('.active-cell').addClass('loaded');
        });}
   }
}

function drillDown(e) {



    var link = $(e && e.tagName ? e : this),
            //cell = link.closest('td'),
            row = link,
            func = link.attr('data-func');
    data = link.attr('geo_location_id');
    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
    // state of selected row
    if (row.hasClass('selected-' + func)) {

        row.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func + ':first').remove();

    } else {

        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

        // remove all loading content
        row.nextAll('.loading-row').remove();

        // let add new one
        row.after('<tr class="loading-row"><td colspan="' + spanColumn + '">' + getLoadingIndicator() + '</td></tr>');

        // toggle state
        row.addClass('selected selected-' + func)
                .children('.active-cell').removeClass('loaded');

        row.addClass('active-cell loaded');

       


        $.get(app.baseUrl+'/admin/counter/ajaxlistcounter', {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
           leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {

            // remove all loading content
            row.nextAll('.loading-row').remove();

            // insert new one
            row
                    .after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                            '<td colspan="' + spanColumn + '"  geo_location_id="'+data+'">' +
                            htmlData +
                            '</td>' +
                            '</tr>');

            //set as loaded
            row.children('.active-cell').addClass('loaded');
            CounterAddressSearch='';
        });
    }
}


function drillDownPjax(e) {



    var link = $(e && e.tagName ? e : this),
            //cell = link.closest('td'),
            row = link,
            func = link.attr('data-func');
            data = link.attr('geo_location_id');
            beginData = $('#beginDate').val();
            endData = $('#endDate').val();
            get = parseGetParams();
    
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
    // state of selected row
    if (row.hasClass('selected-' + func)) {

        row.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func + ':first').remove();

    } else {

        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

        // remove all loading content
        row.nextAll('.loading-row').remove();

        // let add new one
        
         row.after('<tr class="loading-row"><td colspan="' + spanColumn + '">' + getLoadingIndicator() + '</td></tr>');
        // toggle state
       

        row.addClass('active-cell loaded');


       
        
        row.after('<tr class="appended-row appended-row-main-' + func + '" data-func="' + func + '">' +
                            '<td colspan="' + spanColumn + '" geo_location_id="'+data+'"  id="target'+data+'">' +
                            '</td>' +
                            '</tr>');
                                              
       
      
        
       
       
       $.pjax({
        type       : 'GET',
        url        : app.baseUrl+'/admin/counter/ajaxlistcounter',
        container  : '#target'+data,
        data       : {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        },
        push       : false, 
        timeout    : 10000,
        "scrollTo" : false});
        
        
        $(document).on('pjax:send', function() {
            row.children('.active-cell').addClass('loaded');
          })
          $(document).on('pjax:complete', function() {
             row.nextAll('.loading-row').remove();
          })
        
        
        
    }
}


function filterPjax(e) {

   

    var link = $(e && e.tagName ? e : this),
            cell = link.closest('div').parent(),
            row = link,
            func = link.attr('data-func');
            data = link.attr('geo_location_id');
            beginData = $('#beginDate').val();
            endData = $('#endDate').val();
            get = parseGetParams();
    //alert(cell.attr('geo_location_id'));
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
    // state of selected row
    if (row.hasClass('selected-' + func)) {

        row.removeClass('active-cell loaded');
        row.removeClass('selected-' + func)
                .toggleClass('selected', row.children('.active-cell').length)
                .nextAll('.appended-row-main-' + func + ':first').remove();


        var spanColumn = (row.children('td:visible').length || 12),
                // filterData = $("#filter-form").serializeFormJSON(),
                cTitleId = row.find('.title_id').html(),
                cSeasonId = row.find('.season_id').html(),
                cChildrenTitleId = row.find('.children_title_id').html(),
                cReleaseVersionId = row.find('.release_version_id').html();

        row.find('[data-filter]').each(function () {
            filterData[$(this).data('filter')] = $(this).html();
        });

             }                                 
       
           $.get(app.baseUrl+'/admin/counter/ajaxlistcounter', {
            flat:$('input[name="UserCountersSearch[flat]"]').val(),
            user_modem_id:$('input[name="UserCountersSearch[user_modem_id]"]').val(),
            serial_number:$('input[name="UserCountersSearch[serial_number]"]').val(),
            real_serial_number:$('input[name="UserCountersSearch[real_serial_number]"]').val(),
            updated_at:$('input[name="UserCountersSearch[updated_at]"]').val(),
            fulladdress:$('input[name="UserCountersSearch[fulladdress]"]').val(),
            geo_location_id:cell.attr('geo_location_id'),
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            sort:get['sort'],
           leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
               lang:curLang,
        }, function (htmlData) {
         
          
          $('#browse-flatCounter-grid').parent().html(htmlData);
          $('#browse-flatCounter-grid'+cell.attr('geo_location_id')).parent().html(htmlData);
           
        });
       
      
       
       /*$.pjax({
        type       : 'GET',
        url        : app.baseUrl+'/admin/counter/ajaxlistcounter',
        container  : '#target'+cell.attr('geo_location_id'),
        data       : {
            flat:$('input[name="UserCountersSearch[flat]"]').val(),
            user_modem_id:$('input[name="UserCountersSearch[user_modem_id]"]').val(),
            serial_number:$('input[name="UserCountersSearch[serial_number]"]').val(),
            real_serial_number:$('input[name="UserCountersSearch[real_serial_number]"]').val(),
            updated_at:$('input[name="UserCountersSearch[updated_at]"]').val(),
            geo_location_id:cell.attr('geo_location_id'),
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
        },
        push       : false, 
        timeout    : 100000,
        "scrollTo" : false});
    
     $(document).on('pjax:send', function() {
            row.children('.active-cell').addClass('loaded');
          })
          $(document).on('pjax:complete', function() {
             row.nextAll('.loading-row').remove();
          })
        
    }*/
}

function counterExport(e) {



    var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
            row = cell.parent(),
            func = link.attr('data-func');
    data = link.attr('geo_location_id');
    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   
  
        $.get(app.baseUrl+'/admin/export/ajaxlistcounterbyfile', {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            user_type:get['user_type'],
           leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {
            //window.location = app.baseUrl + '/admin/export/export';
            window.location = app.baseUrl + '/admin/export/exportcsv?title='+htmlData;

             //window.location='export';
            //window.location='exportexcel?title='+htmlData;
         
        });
    
}

    function counterExport1C(e) {


        var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
            row = cell.parent(),
            func = link.attr('data-func');
        data = link.attr('geo_location_id');
        beginData = $('#beginDate').val();
        endData = $('#endDate').val();
        get = parseGetParams();

        if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
        if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
        if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
        if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
        if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}


        $.get(app.baseUrl+'/admin/export/ajaxlistcounterbyfile1c', {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            user_type:get['user_type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {
            window.location = app.baseUrl + '/admin/export/export';

        });

    }

function exportExcel(e) {

    e.stopPropagation();

    var link = $(e && e.tagName ? e : this),
            cell = link.closest('td'),
            row = cell.parent(),
            func = link.attr('data-func');
    data = link.parent().attr('geo_location_id');
    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    

   
  
        $.get(app.baseUrl+'/admin/export/ajaxlistcounterbyexcel', {
            geo_location_id: data,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            user_type:get['user_type'],
            type:get['type'],
            lang:curLang,

        }, function (htmlData) {
               
              window.location=app.baseUrl + '/admin/export/exportexcel?title='+htmlData;
         
        });
    
}

function submitFilterGraph(e) {

    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    city = $('#city').val();
    address = $('#address').val();
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   // leak = $('#leak_checkbox').prop('checked');
   // tamper = $('#tamper_checkbox').prop('checked');
    //magnet = $('#magnet_checkbox').prop('checked');
    //lowBatteryLevel = $('#lowBatteryLevel_checkbox').prop('checked');
   // disconnect = $('#disconnect_checkbox').prop('checked');
    
        
        $.post(app.baseUrl+'/admin/counter/graph', {
            //geo_location_id: data,
            id:address,
            region_id:city,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {
            
            $("#browse-address-grid").replaceWith(htmlData);

        });
     
}


function submitFilter(e) {

    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    user_type = $('#user_type').val();
    city = $('#city').val();
    address = $('#address').val();
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   // leak = $('#leak_checkbox').prop('checked');
   // tamper = $('#tamper_checkbox').prop('checked');
    //magnet = $('#magnet_checkbox').prop('checked');
    //lowBatteryLevel = $('#lowBatteryLevel_checkbox').prop('checked');
   // disconnect = $('#disconnect_checkbox').prop('checked');

            user_type=get['user_type']

        
        $.post(app.baseUrl+'/admin/counter/index', { 
            //geo_location_id: data,
            id:address,
            region_id:city,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            user_type:user_type,
            tamper:tamper,
            magnet:magnet,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {
            
            $("#browse-address-grid").replaceWith(htmlData);

        });
     
}

function submitFilterAllCounters(e) {

    beginData = $('#beginDate').val();
    endData = $('#endDate').val();
    get = parseGetParams();
    city = $('#city').val();
    exploitation = $('#exploitation').val();
    address = $('#address').val();
    if($('#leak_checkbox').prop('checked')){leak =1;}else{leak =0;}
    if($('#tamper_checkbox').prop('checked')){tamper =1;}else{ tamper =0;}
    if($('#magnet_checkbox').prop('checked')){magnet =1;}else{ magnet =0;}
    if($('#lowBatteryLevel_checkbox').prop('checked')){lowBatteryLevel =1;}else{ lowBatteryLevel =0;}
    if($('#disconnect_checkbox').prop('checked')){disconnect =1;}else{ disconnect =0;}
   // leak = $('#leak_checkbox').prop('checked');
   // tamper = $('#tamper_checkbox').prop('checked');
    //magnet = $('#magnet_checkbox').prop('checked');
    //lowBatteryLevel = $('#lowBatteryLevel_checkbox').prop('checked');
   // disconnect = $('#disconnect_checkbox').prop('checked');
    
        
        $.get(app.baseUrl+'/admin/counter/allcounterlist', { 
            //geo_location_id: data,
            id:address,
            region_id:city,
            beginDate: beginData + ' 00:00:00',
            endDate: endData + ' 23:59:59',
            type:get['type'],
            leak:leak,
            tamper:tamper,
            magnet:magnet,
            exploitation:exploitation,
            lowBatteryLevel:lowBatteryLevel,
            disconnect:disconnect,
            lang:curLang,
        }, function (htmlData) {
            
            $("#all-counter-grid").replaceWith(htmlData);

        });
     
}

function sort(e) {

    

    var     link = $(e && e.tagName ? e : this),
            url=link.attr('href');
            
            cell = link.closest('div').parent(),

            
    // state of selected row
       
        $.get(url, {

        }, function (htmlData) {
            $('#browse-flatCounter-grid'+cell.attr('geo_location_id')).parent().html(htmlData);
            $("#all-counter-grid").parent().html(htmlData);
        });
    
}

function ajaxPagination(e) {

    

    var     link = $(e && e.tagName ? e : this),
            url=link.attr('url'),
            target =link.attr('parentId');

            
    // state of selected row
       
        $.get(url, {
        //data: eval(alerts_type+'A'),
        }, function (htmlData) {
            
            $('#'+target).empty();
            $('#'+target).replaceWith(htmlData);
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

function toggleChartAndMap(e) {
$('#ChartAndMap').slideToggle("slow");

    if($('#ChartAndMapToggleButton').html()=='Свернуть'){
            $('#ChartAndMapToggleButton').html('Развернуть');
        }
        else{
            $('#ChartAndMapToggleButton').html('Свернуть');
        }
}


function selectRegion(e) {

    var data = $('#region :selected').val();

        
    // state of selected row
   
        $.get(app.baseUrl+'/admin/counter/ajaxlistcity', {
            geo_location_id: data
        }, function (htmlData) {
               $('#city').empty();
               $('#city').append(htmlData);
        });
}

function selectCity(e) {
   
    var data = $('#city :selected').val();

        $('#house').empty();
    // state of selected row
   
        $.get(app.baseUrl+'/admin/counter/ajaxlisthouse', {
            geo_location_id: data
        }, function (htmlData) {
               $('#house').empty();
               $('#house').append(htmlData);
        });
}

function getLoadingIndicator(className) {
    return '<div' + (className ? ' class="' + className + '"' : '') + ' style="text-align: center">'
            + '<span>Loading...</span>'
            + '<img src="' + BASE_URL + '/images/loading-icon.gif" width="15" height="15">'
            + '</div>';
}


    function startTime()
    {
        var tm=new Date();
        var y=tm.getFullYear();
        var month=tm.getMonth()+1
        var d=tm.getDate();
        var h=tm.getHours();
        var m=tm.getMinutes();
        var s=tm.getSeconds();
        m=checkTime(m);
        s=checkTime(s);
        d=checkTime(d);
        month=checkTime(month);
       // document.getElementById('clock').innerHTML="<i class=\"fa fa-clock\"></i>"+y+"-"+month+"-"+d+" | "+h+":"+m+":"+s;
        //t=setTimeout(function(){startTime()},500);
    }
    function checkTime(i)
    {
        if (i<10)
        {
            i="0" + i;
        }
        return i;
    }


function reset() {
   $('#city').val(0);
    $('#region').val(0);
    $('#exploitation').prop("checked", false);
    $('#user_type').val('');
   $('#CounterAddressSearch').find('input').each(function () {
    $(this).val('');
});

$('#CounterAddressSearch').find('input:checkbox').each(function () {
    $(this).prop('checked',false);
});

var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();
date.setUTCDate(1);

var monthAgo =  date;
var resM = monthAgo.toISOString().slice(0,10).replace(/-/g,"-");

var rightNow = new Date();
var res = rightNow.toISOString().slice(0,10).replace(/-/g,"-");


 beginData = $('#beginDate').val(resM);
 endData = $('#endDate').val(res);

   $('#filterSubmit').trigger('click');
    $('#allCounterFilterSubmit').trigger('click');
   return false;
}
});