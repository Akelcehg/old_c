$(document).ready(function () {
	$.ajax({
	type: 'Get',
	url:app.baseUrl +'/admin/counter/ajaxcountertomap',
	dataType: "json",
	success: function(response, status) {
		placemarks=response;
		for (var i=0;i<placemarks.length;i++) {
						
			placemarks[i].id = new ymaps.Placemark([placemarks[i].latitude,placemarks[i].longitude], {
				content:placemarks[i].address,
				balloonContent:placemarks[i].address,
			},
			{
				//preset:option
			});
			
			myMap.geoObjects.add(placemarks[i].id);
		}
	}});
});