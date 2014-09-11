    var activeInfoWindow;
    var map;

    function addMarker(id, permalink, timeAgo, location, latitude, longitude, cpm, usvh, graphPath, status) {
        var infowindow = new google.maps.InfoWindow({
                content: '<div id="content">'+
                    '<h1><a href="'+permalink+'">'+location+'</a></h1>'+
                    '<h2>'+timeAgo+'</h2>'+
                    '<div id="bodyContent">'+
                    '<div><b>'+cpm+'</b> CPM</div>'+
                    '<div ><b>'+usvh+'</b> µSv/h</div>'+
                    '<img class="img-responsive" src="'+graphPath+id+'_small.png" />'+
                    '</div>'+
                    '</div>',
            maxWidth: 400
        });

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(latitude, longitude),
            icon: {
                strokeColor: 'black',
                    strokeOpacity: .6,
                    fillColor: status,
                    fillOpacity: .7,
                    path: google.maps.SymbolPath.CIRCLE,
                    strokeWeight: 2,
                    scale: 10
                },
            map: map,
            flat: true, 
            title: location,
            draggable: false
        });

        google.maps.event.addListener(marker, 'click', function() {
            map.panTo(marker.position);

            if (activeInfoWindow == infowindow) {
                return;
            }
                
            if (activeInfoWindow) {
                activeInfoWindow.close();
            }
                
            infowindow.open(map, this);
            activeInfoWindow = infowindow;
        });
    }

    function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(37.4230, 141.0329),
          zoom: 5,
          styles:
          [{featureType:"poi",stylers:[{saturation:-100},{lightness:51},{visibility:"simplified"}]},
          {featureType:"road.highway",stylers:[{saturation:-100},{visibility:"simplified"}]},
          {featureType:"road.arterial",stylers:[{saturation:-100},{lightness:30},{visibility:"on"}]},
          {featureType:"road.local",stylers:[{saturation:-100},{lightness:40},{visibility:"on"}]},
          {featureType:"transit",stylers:[{saturation:-100},{visibility:"simplified"}]}]
        };

        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        // Add the 10km circle
        var cityCircle10 = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.4,
            strokeWeight: 1,
            fillColor: '#FF0000',
            fillOpacity: 0.1,
            map: map,
            center: new google.maps.LatLng(37.4230, 141.0329),
            radius: 10*1000
        });
            
        // Add the 20km circle
        var cityCircle20 = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0.6,
            strokeWeight: 1,
            fillColor: '#FF0000',
            fillOpacity: 0.1,
            map: map,
            center: new google.maps.LatLng(37.4230, 141.0329),
            radius: 20*1000
        });
    }
      
    google.maps.event.addDomListener(window, 'load', initialize);