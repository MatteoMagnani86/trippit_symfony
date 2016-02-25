
// Google Autocomplete Place API

function initAutocomplete()
{
	// Create the autocomplete object, restricting the search to geographical location types.
	autocomplete = new google.maps.places.Autocomplete
	(
		/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
		{types: ['geocode']}
	);

	// When the user selects an address from the dropdown, populate the address fields in the form
	autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() 
{	
	// get the place details from the autocomplete object
	var place = autocomplete.getPlace();
	
	// recupero i parametri da inserire nel database
	var name = place.name;
	var lat = place.geometry.location.lat();
	var lon = place.geometry.location.lng();
	
	// siccome l'indirizzo è complesso, per recuperare il 'country' devo fare alcuni controlli
	for(var i = 0; i < place.address_components.length; i += 1)
	{
		var addressObj = place.address_components[i];
		for(var j = 0; j < addressObj.types.length; j += 1) 
		{
			if (addressObj.types[j] === 'country') 
			{
				var country = addressObj.long_name;
			}
		}
	}
	
	$('#autocomplete').val(name + ", " + country + ", " + lat + ", " + lon);	
}

function initAutocompletePlace() 
{
	
// Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input); 
  
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };
	  
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
  });
  // [END region_getplaces]
}