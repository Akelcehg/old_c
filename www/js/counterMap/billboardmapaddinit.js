ymaps.ready(init);
var myMap, 
myPlacemark,
myPlacemark2,
myCollection;

function init(){ 
	myMap = new ymaps.Map ("map", {

		center: [46.484862186165834, 30.7345425936334],
		zoom: 13

	}); 

	var myCollection = new ymaps.GeoObjectCollection();

	myMap.controls.add('typeSelector');

	myMap.controls.add('zoomControl');

	myMap.events.add('click', function (e) {

		var coords = e.get('coords');

		$('#lat').val(coords[0].toPrecision(20));

		$('#long').val(coords[1].toPrecision(20));
		
                var myStreetGeocoder = ymaps.geocode([coords[0].toPrecision(20),coords[1].toPrecision(20)]);
               
                
                myStreetGeocoder.then(
                            function (res) {
                                var nearest = res.geoObjects.get(0);
                                var name = nearest.properties.get('name');
                                var houseBegin = name.indexOf(",");
                                var streetBegin = name.indexOf("улица");
                                var house = name.substring(houseBegin+1);

                                switch (streetBegin) {
                                  case -1:
                                    street=name.substring(0,houseBegin);
                                    break
                                  case 0:
                                    street=name.substring(6,houseBegin);
                                    break
                                  default:
                                    street=name.substring(0,streetBegin-1);  
                                }

                              $('#street').val(street);
                              $('#house').val(house);

                            },
                            function (err) {
                                alert('Ошибка');
                            });
 
		

	});

	myMap.events.add('click', function (e) {

		var coords = e.get('coordPosition');

		myCollection.removeAll(); 

		myPlacemark = new ymaps.Placemark([coords[0],coords[1]], {
			content:'новый билбоард ',
		}, { preset: 'twirl#yellowIcon',});

		myCollection.add(myPlacemark);

		myMap.geoObjects.add(myCollection);

	});
        
        
        
          get = parseGetParams();
    
    if(get['id']===undefined)
    {
        data={};
    }
    else
    {
        data='id=' +get['id'];
    }
    
    $.ajax({
        type: 'Get',
        url: app.baseUrl + '/admin/counter/ajaxcountertomap',
        data: data,
        dataType: "json",
        success: function (response, status) {
            placemarks = response;
            for (var i = 0; i < placemarks.length; i++) {


                var option = '';
                //var button='<p><a href="/admin/counter?geo_location_id='+placemarks[i].id+'" class="go_link">Перейти к дому</a></p>';
                var button = '<p><a href="javascript:;" onclick="goToAddress(' + placemarks[i].id + ')"class="go_link">Перейти к дому</a></p>';


                placemarks[i].id = new ymaps.Placemark([placemarks[i].latitude, placemarks[i].longitude], {
                    content: placemarks[i].street + ' ' + placemarks[i].house,
                    balloonContent: placemarks[i].street + ' ' + placemarks[i].house + '<br/>' + button,
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