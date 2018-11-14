$(function(){

	objMapData.forEach(function(location,index){
		objMarkers[location.id].latLng = L.latLng({lat: parseFloat(location.lat), lng: parseFloat(location.lng)});
	});

	objMap = L.map($map[0]);

	objMapBounds = L.latLngBounds();
	for(var i in objMarkers){
		objMapBounds.extend(objMarkers[i].latLng);
		objMarkers[i].marker = L.marker(objMarkers[i].latLng,{
		  title: objMarkers[i].name
		}).addTo(objMap);

		objMarkers[i].marker.on('click', function() {
		  alert("CLIC");
		});
	}
	
	objMap.setView(objMapBounds.getCenter(), objMapConfig.map.zoom);
	L.tileLayer(objMapConfig.tileLayer.url, objMapConfig.tileLayer).addTo(objMap);

	objMap.fitBounds(objMapBounds);
	objMap.zoomControl.setPosition('bottomleft');
});
