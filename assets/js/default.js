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
		$map.outerHeight(0).outerHeight(mapHeight);
	}).trigger('resize');

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