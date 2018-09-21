// ------------------------------------------------------------------------------------------------------------------------------
// DATA SETTINGS
var arrCountries = [];
var arrCountriesAvailable = [];
var objContinents = {};
var objCountries = {};
var objMarkers = {};
var objMap;
var $map = document.querySelector('.mod_wem_locations_map');
var $reset = $map.querySelector('.map__reset');
var $content = $reset.querySelector('.map__content');
var $dropdowns = $content.querySelector('.map__dropdowns');

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

objMap = L.map(document.querySelector('.mod_wem_locations_map'));
objMapBounds = L.latLngBounds();
for(var i in objMarkers){
  objMapBounds.extend(objMarkers[i].latLng);
  objMarkers[i].marker = new L.Marker(objMarkers[i].latLng,{
    title: objMarkers[i].name
  }).addTo(objMap);

  objMarkers[i].marker.addListener('click', function() {
    alert("CLIC");
  });
}
objMap.setView(objMapBounds.getCenter());
objMap.fitBounds(objMapBounds);

$map.appendChild($reset);
$map.appendChild($content);
$map.appendChild($dropdowns);
