$(function(){
	objMapData.forEach(function(location,index){
		objMarkers[location.id].latLng = L.latLng({lat: parseFloat(location.lat), lng: parseFloat(location.lng)});
	});

	objMap = L.map($map[0]);
	objMapBounds = L.latLngBounds();
	var options = {};

	for(var i in objMarkers){
		objMapBounds.extend(objMarkers[i].latLng);
		options = {};
		options.title = objMarkers[i].title;
		
		if(objMarkers[i].category.marker && objMarkers[i].category.marker.icon)
			options.icon = L.icon(objMarkers[i].category.marker.icon);

		objMarkers[i].marker = L.marker(objMarkers[i].latLng, options).addTo(objMap);

		objMarkers[i].marker.on('click', function() {
		  alert("CLIC");
		});
	}
	
	objMap.setView(objMapBounds.getCenter(), objMapConfig.map.zoom);
	L.tileLayer(objMapConfig.tileLayer.url, objMapConfig.tileLayer).addTo(objMap);

	objMap.fitBounds(objMapBounds);
	objMap.zoomControl.setPosition('bottomleft');
});
