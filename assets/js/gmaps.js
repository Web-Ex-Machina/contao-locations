// https://developers.google.com/maps/documentation/javascript/reference/3.exp/map

// ------------------------------------------------------------------------------------------------------------------------------
// DATA SETTINGS
var arrCountries = [];
var arrCountriesAvailable = [];
var objContinents = {};
var objCountries = {};
var objMarkers = {};
var objMap;
var objMapCenter;
var objMapBounds;
var $map = $('.mod_wem_locations_map');
var $reset = $map.next('.map__reset');
var $content = $reset.next('.map__content');
var $dropdowns = $content.next('.map__dropdowns');

$(function(){

  // ------------------------------------------------------------------------------------------------------------------------------
  // RESIZE EVENT
  $(window).resize(function(){
    var mapHeight = window.innerHeight;
    if($('#header').length)
      mapHeight -= $('#header').outerHeight();
    if($('#footer').length)
      mapHeight -= $('#footer').outerHeight();
    $('.mod_wem_locations_map').outerHeight(0).outerHeight(mapHeight);
  }).trigger('resize');

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

	objMap = new google.maps.Map($('.mod_wem_locations_map')[0]);

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

	$map.append($reset);
	$map.append($content);
	$map.append($dropdowns);
});

// ------------------------------------------------------------------------------------------------------------------------------
// UTILITIES
$.fn.filterByData = function(prop, val) {
  return this.filter(
      function() { return $(this).data(prop)==val; }
  );
}

Object.hasKey = function(obj,key){
  if(Object.keys(obj).indexOf(key) != -1)
    return true;
  else
    return false;
}
