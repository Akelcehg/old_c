/**
 * Created by alexey on 19.05.16.
 */


$(function () {

    start();

    function start(){
        checkMD()
    };

    function checkMD() {

        $.get(app.baseUrl+'/prom/rtchart/ajaxcheck', {

        }, function (htmlData) {

            $('#ForcedMDContainer').replaceWith(htmlData);

        });

       setTimeout(checkMD,5000);
    }

});
