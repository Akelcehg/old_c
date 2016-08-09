/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {

    $(document.body).on('click', '#exportCSV', counterCSV);
    $(document.body).on('click', '#export1Cm', counterExport1Cm);

    $(document.body).on('click', '.monthD', getReports);
    $(document.body).on('click', '.yearD', getReports);

    $(document.body).on('click', '.year', getReportsMonth);

    $(document.body).on('click', '.monthE', getEmergency);
    $(document.body).on('click', '.yearE', getEmergency);

    $(document.body).on('click', '.monthDiag', getDiagnostic);
    $(document.body).on('click', '.yearDiag', getDiagnostic);

    $(document.body).on('click', '.monthI', getIntervention);
    $(document.body).on('click', '.yearI', getIntervention);


    function getReports(e) {

        get = parseGetParams();
        var link = $(e && e.tagName ? e : this),
            date = link.attr('date'),
            dateM = link.attr('dateM');
       $('.yearD[date='+date+']').attr('dateM',dateM);

        $.get(app.baseUrl+'/prom/correctors/ajaxreport', {

            id:get['id'],
            year:date,
            month:dateM,

        }, function (htmlData) {

           $('#browse-day-reports-grid').replaceWith(htmlData);
        });

    }

    function getEmergency(e) {

        get = parseGetParams();
        var link = $(e && e.tagName ? e : this),
            date = link.attr('date'),
            dateM = link.attr('dateM');
        $('.yearD[date='+date+']').attr('dateM',dateM);

        $.get(app.baseUrl+'/prom/correctors/ajaxemergency', {

            id:get['id'],
            year:date,
            month:dateM,

        }, function (htmlData) {

            $('#emergencyTable').replaceWith(htmlData);
        });

    }


    function getDiagnostic(e) {

        get = parseGetParams();
        var link = $(e && e.tagName ? e : this),
            date = link.attr('date'),
            dateM = link.attr('dateM');
        $('.yearD[date='+date+']').attr('dateM',dateM);

        $.get(app.baseUrl+'/prom/correctors/ajaxdiagnostic', {

            id:get['id'],
            year:date,
            month:dateM,

        }, function (htmlData) {

            $('#diagnosticTable').replaceWith(htmlData);
        });

    }

    function getIntervention(e) {

        get = parseGetParams();
        var link = $(e && e.tagName ? e : this),
            date = link.attr('date'),
            dateM = link.attr('dateM');
        $('.yearD[date='+date+']').attr('dateM',dateM);

        $.get(app.baseUrl+'/prom/correctors/ajaxintervention', {

            id:get['id'],
            year:date,
            month:dateM,

        }, function (htmlData) {

            $('#interventionTable').replaceWith(htmlData);
        });

    }

    function getReportsMonth(e) {

        get = parseGetParams();

        var link = $(e && e.tagName ? e : this),
            date = link.attr('date');

        $.get(app.baseUrl+'/prom/correctors/ajaxreportmonth', {

            id:get['id'],
            year: date,


        }, function (htmlData) {

            $('#browse-month-reports-grid').replaceWith(htmlData);

        });

    }

    function counterExport1C(e) {
        $.get(app.baseUrl+'/prom/correctors/ajaxlistcorrectorsbyfile1c', {
            id:$('#search_q').val(),
        }, function (htmlData) {
            window.location = app.baseUrl + '/prom/correctors/export1c';

        });
    }

    function counterCSV(e) {
        $.get(app.baseUrl+'/prom/correctors/ajaxlistcorrectorsbycsv', {
            id:$('#search_q').val(),
        }, function (htmlData) {
            window.location = app.baseUrl + '/prom/correctors/exportcsv';

        });
    }



    function counterExport1Cm(e) {
        $.get(app.baseUrl+'/prom/correctors/ajaxlistcorrectorsbyfile1cm', {
            id:$('#search_q').val(),
        }, function (htmlData) {
            window.location = app.baseUrl + '/prom/correctors/export1c';

        });
    }

});