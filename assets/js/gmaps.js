// https://developers.google.com/maps/documentation/javascript/reference/3.exp/map
$(function(){
    $.each(objMapData,function(index,location){
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
		  latLng: new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.lng))
		};
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