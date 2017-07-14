jQuery(document).ready(function() {
	if (jQuery(".google_map").length>0) {
		jQuery(".google_map").each(function(){
			var thisMap = jQuery(this);
			var thisAddress = thisMap.text().split("|");
			var thisId = thisMap.attr("id");
			var thisZoom = thisMap.attr("data-zoom")*1;
			var thisControls = thisMap.attr("data-controls");
				if (thisControls=="true") { thisControls=false; } else { thisControls=true; }
			var thisMarker = thisMap.attr("data-marker");
				if (thisMarker=="true") { thisMarker=true; } else { thisMarker=false; }
			var thisIcon = thisMap.attr("data-icon");
			
			var myOptions={zoom:thisZoom,center:new google.maps.LatLng(0,0),mapTypeId:google.maps.MapTypeId.ROADMAP,disableDefaultUI:thisControls,mapTypeControl:false};
			var map=new google.maps.Map(document.getElementById(thisId),myOptions);
			var bounds = new google.maps.LatLngBounds();
			
			jQuery.getJSON('//maps.googleapis.com/maps/api/geocode/json?address='+thisAddress[0]+'&sensor=false', null, function (data) {
				var p = data.results[0].geometry.location
				var latlng = new google.maps.LatLng(p.lat,p.lng);
				if (thisMarker) { var marker = new google.maps.Marker({position:latlng,map:map,icon:thisIcon}); }
				map.setCenter(latlng);
			});
		});	
	}
});