$(function(){
	objMapData.forEach(function(location,index){
		if(!Object.hasKey(objContinents, location.continent.code)){
		  objContinents[location.continent.code] = location.continent;
		  objContinents[location.continent.code].countries = {};
		}
		if(!Object.hasKey(objContinents[location.continent.code].countries, location.country.code)){
		  objContinents[location.continent.code].countries[location.country.code] = location.country;
		  objCountries[location.country.code] = location.country;
		  arrCountries.push(location.country.code);
		  arrCountriesAvailable.push(location.country.code);
		}
		objMarkers[location.country.code+'-'+location.name.toLowerCase().replace(/\s/g,'_')]={
		  country: location.country.code,
		  continent: location.continent.code,
		  name: location.name,
		  latLng: L.latLng({lat: parseFloat(location.lat), lng: parseFloat(location.lng)})
		};
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
