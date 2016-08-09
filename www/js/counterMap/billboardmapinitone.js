
    $('a[href=#map1]').on('shown.bs.tab',function(){

        ymaps.ready(init);

    });

    $('a[href=#map1]').on('hidden.bs.tab',function(){

        myMap.destroy()

    });

    function init() {
        myMap = new ymaps.Map("map", {
            center: [46.484862186165834, 30.7345425936334],
            zoom: 15,
            controls: ['smallMapDefaultSet']
        });

        $.ajax({
            type: 'POST',
            url: app.baseUrl + '/admin/counter/ajaxcountertomap',
            data:{
                id:mapAddressId,
                status:['ACTIVE','DEACTIVATED'],
                isCounter:false
            },
            dataType: "json",
            success: function (response, status) {
                placemarks = response;
                for (var i = 0; i < placemarks.length; i++) {


                    var option = '';



                    placemarks[i].id = new ymaps.Placemark([placemarks[i].latitude, placemarks[i].longitude], {
                            content: placemarks[i].street + ' ' + placemarks[i].house,
                            balloonContent: placemarks[i].street + ' ' + placemarks[i].house + '<br/>',
                        },
                        {
                            preset: option
                        }
                    );
                    myMap.geoObjects.add(placemarks[i].id);
                }
                if (placemarks.length > 0) {
                    if (placemarks.length >= 2) {
                        myMap.setBounds(myMap.geoObjects.getBounds());
                    } else {
                        myMap.setCenter([placemarks[0].latitude, placemarks[0].longitude]);
                    }

                }
            }
        });

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






