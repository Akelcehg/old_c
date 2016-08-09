/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    CalculateAll();

    $(document.body).on('click', '.monthD', getReports);
    $(document.body).on('click', '.yearD', getReports);


    $('#prom').on('change', CalculateAll);
   // $('#grs').on('change', CalculateAll);
    $('#legal_entity').on('change', CalculateAll);
    $('#house_metering').on('change', CalculateAll);
    $('#individual').on('change', CalculateAll);


    function getReports(e) {


        var link = $(e && e.tagName ? e : this),
            date = link.attr('date'),
            dateM = link.attr('dateM');
        $('.yearD[date='+date+']').attr('dateM',dateM);

        $.get(app.baseUrl+'/prom/summary/summaryindex', {
           beginDate:date+'-'+dateM+'-01',


        }, function (htmlData) {

            $('#browse-month-reports-grid').replaceWith(htmlData);
        });

    }


    function CalculateAll() {


        var grs=parseFloat($('#grs').val())
        var prom =parseFloat($('#prom').val())
        var legalEntity=parseFloat($('#legal_entity').val())
        var houseMetering=parseFloat($('#house_metering').val())
        var individual=parseFloat($('#individual').val())

        sum = prom+legalEntity+houseMetering+individual;

        $('#all').val(Number(sum).toFixed(2))
    }

});

