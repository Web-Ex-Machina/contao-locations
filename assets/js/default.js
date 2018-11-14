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
var $map = $('.map__container');
var $list = $map.next('.map__list');
var $reset = $list.next('.map__reset');
var $toggleList = $reset.next('.map__toggleList');
//var $dropdowns = $list.next('.map__dropdowns');

$(function(){
	// ------------------------------------------------------------------------------------------------------------------------------
	// RESIZE EVENT
	$(window).resize(function(){
		var mapHeight = window.innerHeight;
		if($('#header').length)
			mapHeight -= $('#header').outerHeight();
		if($('#footer').length)
			mapHeight -= $('#footer').outerHeight();
		$map.parent().outerHeight(0).outerHeight(mapHeight);
	}).trigger('resize');

	$toggleList.bind('click', function(){
		$(this).toggleClass('active');
		$list.toggleClass('active');
		$map.toggleClass('full');
	});

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
		objMarkers[location.id]={
			country: location.country.code,
			continent: location.continent.code,
			name: location.name,
		};
	});
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