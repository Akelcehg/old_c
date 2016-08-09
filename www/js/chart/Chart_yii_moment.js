$(function () {

    init();

    var date = moment.unix($("#date").val());
    var next = moment(date).add(1,chartMode);
    var prev = moment(date).subtract(1,chartMode);

    moment.locale(curLocale);

    $(document.body).on('click', '#next', nextF);
    $(document.body).on('click', '#prev', prevF);

    $(document.body).on('click', '#weekMode',function(){chartMode='week';init()});

    $(document.body).on('click', '#dayMode',function(){chartMode='day';init()});

    $(document.body).on('click', '#monthMode',function(){chartMode='month';init()});

    $(document.body).on('click', '#now',function(){date = $("#date").val(moment()/1000);init()});

    function nextF(){
        $("#date").val($("#next").attr("value")/1000);
        init();

    }

    function prevF(){
        $("#date").val($("#prev").attr("value")/1000);
        init();

    }

    function label(time){

        switch (chartMode){

            case 'day' :
                return time.format("YYYY-MM-DD")
                break

            case 'week' :
                return time.format("DD")+" "+time.format("MMM")+ "-" +moment(time).add(6,"day").format("DD")+" "+moment(time).add(6,"day").format("MMM")
                break

            case 'month' :
                return time.format("MM.YYYY")
                break
        }
    }



    function init(){

        switch (chartMode){

            case 'day' :
                date = moment.unix($("#date").val());
                break

            case 'week' :
                date = firstDayOfWeek()
                break

            case 'month' :
                date =   firstDayOfMonth()
                break
        }

        next = moment(date).add(1,chartMode);
        prev = moment(date).subtract(1,chartMode);
        $("#datelabel").html(label(date));
        $("#next").html(label(next));
        $("#next").attr("value",next);
        $("#prev").html(label(prev));
        $("#prev").attr("value",prev);
        checkNext();
        checkNow();

    }

    function checkNext(){

       if($("#next").attr("value")<= moment()){

           $("#next").show();
           $("#nextC").show();
    }else{
           $("#next").hide();
           $("#nextC").hide();
       }
    }


    function checkNow(){

        if(moment(date).isBefore(moment(),chartMode)){

            $("#now").show();

        }else{
            $("#now").hide();

        }
    }

})

function firstDayOfMonth(){
    date = moment.unix($("#date").val());
    dayOfMonth = date.format("D");
    return date.subtract(parseInt(dayOfMonth,10)-1,'day');
}

function firstDayOfWeek(){
    date = moment.unix($("#date").val());
    dayOfWeek = date.format("E");

    return date.subtract(parseInt(dayOfWeek,10)-1,'day');

}





