// https://developers.google.com/maps/documentation/javascript/reference/3.exp/map
$(function(){
	objMapData.forEach(function(location,index){
		objMarkers[location.id].latLng = new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng));
	});

	objMap = new google.maps.Map($map[0]);

	objMapBounds = new google.maps.LatLngBounds();
	for(var i in objMarkers){
		objMapBounds.extend(objMarkers[i].latLng);
		objMarkers[i].marker = new google.maps.Marker({
			position: objMarkers[i].latLng,
			map: objMap,
			title: objMarkers[i].name
		});

		objMarkers[i].marker.addListener('click', function() {
			alert("CLIC");
        });
	};

	objMap.setCenter(objMapBounds.getCenter());
	objMap.fitBounds(objMapBounds);
});