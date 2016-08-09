/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {

    $(document.body).on('click', '#getMoments', getMoments);

    var count =600


    function getMoments(e) {

        get = parseGetParams();


        $.get(app.baseUrl + '/prom/report/getmomentdata', {

            id: get['id'],
        }, function (htmlData) {

            $('#getMoments').hide();
            $('#timertext').show();

           // $('#timer').show();

            timer()

        });

    }


    function timer() {

        if(count>0) {




            count=count-1;

            $('#timer').html(Math.floor(count/60)+":"+count%60);
           t=setTimeout(function(){timer()},1000)
        }else{
            $('#getMoments').show();
            $('#timertext').hide();
            $('#timer').hide();
        }
    }



});