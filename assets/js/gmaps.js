$(function(){
	objMapData.forEach(function(location,index){
		objMarkers[location.id].latLng = new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng));
	});

	objMap = new google.maps.Map($map[0]);
	objMapBounds = new google.maps.LatLngBounds();
	var options = {};

	for(var i in objMarkers){
		objMapBounds.extend(objMarkers[i].latLng);
		options = {};

		options.position = objMarkers[i].latLng;
		options.map = objMap;
		options.title = objMarkers[i].title;
		options.locationID = objMarkers[i].id;

		if(objMarkers[i].category.marker && objMarkers[i].category.marker.icon)
			options.icon = objMarkers[i].category.marker.icon.iconUrl;

		objMarkers[i].marker = new google.maps.Marker(options);

		if(0 < $('.map__list').length){
			objMarkers[i].marker.addListener('click', function() {
				$('.map__list .map__list__item').removeClass('active');
				$('.map__list .map__list__item[data-id="'+this.locationID+'"]').addClass('active');
	        });
		}
	};

	objMap.setCenter(objMapBounds.getCenter());
	objMap.fitBounds(objMapBounds);
});