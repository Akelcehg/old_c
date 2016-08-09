$('#calendar').fullCalendar({
    eventLimit:true,
    dayClick: function(date, jsEvent, view) {

      /*  alert('Clicked on: ' + date.format());

        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

        alert('Current view: ' + view.name);*/

       /* $.get(app.baseUrl+'/metrix/operations/getcreateeventform', {

        }, function (htmlData) {

            $('#calendarText').html(htmlData)

        });*/

    }
});
