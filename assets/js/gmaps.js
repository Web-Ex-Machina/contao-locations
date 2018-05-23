// ------------------------------------------------------------------------------------------------------------------------------
// DATA SETTINGS
var arrCountries = [];
var arrCountriesAvailable = [];
var objContinents = {};
var objCountries = {};
var objMarkers = {};
var objMap;
var objMapCenter;
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
		  latLng: [location.lat,location.lng]
		};
	});

	var objMapCenter = findMapCenter();
	objMap = new google.maps.Map($('.mod_wem_locations_map')[0], {
	  zoom: 4,
	  center: objMapCenter
	});

	$map.append($reset);
	$map.append($content);
	$map.append($dropdowns);

	for(var i in objMarkers){
		drawMarker(objMarkers[i]);
	};
});

function findMapCenter(){
	var totalLat = 0;
	var totalLng = 0;
	var total = 0;
	for(var i in objMarkers){
		totalLat += parseFloat(objMarkers[i].latLng[0]);
		totalLng += parseFloat(objMarkers[i].latLng[1]);
		total++;
	}
	return {lat: totalLat / total, lng: totalLng / total};
}

function drawMarker(marker){
	var marker = new google.maps.Marker({
		position: {lat:parseFloat(marker.latLng[0]), lng:parseFloat(marker.latLng[1])},
		map: objMap
	});
}

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