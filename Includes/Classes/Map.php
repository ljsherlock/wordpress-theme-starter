<?php

namespace Includes\Classes;

class Map
{

    public static function setup()
    {
        // Maps
        add_action( 'wp_footer', array(__class__, 'render' ) );
    }

    // wp_footer action here instead of cluttering up the template.
    public static function render() {



            ?>

            <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMhpBMrnA8vGvYFCe5BID6kDMVrzg_w20"></script>
            <script type="text/javascript">

                // Paddington Garden's javascript variables
                var paddingtonGardensTheme = { "templateURI" : '<?= LJS_URL ?>' };

                function initialize() {
                    var center = { lat: 51.5439227, lng: -0.1383555 },
                        styles = [
                          {
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#f5f5f5"
   }
 ]
},
{
 "elementType": "labels.icon",
 "stylers": [
   {
     "visibility": "off"
   }
 ]
},
{
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#616161"
   }
 ]
},
{
 "elementType": "labels.text.stroke",
 "stylers": [
   {
     "color": "#f5f5f5"
   }
 ]
},
{
 "featureType": "administrative.land_parcel",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#bdbdbd"
   }
 ]
},
{
 "featureType": "administrative.neighborhood",
 "elementType": "labels.text",
 "stylers": [
   {
     "color": "#20ae8a"
   }
 ]
},
{
 "featureType": "poi",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#eeeeee"
   }
 ]
},
{
 "featureType": "poi",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#757575"
   }
 ]
},
{
 "featureType": "poi.park",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#e5e5e5"
   }
 ]
},
{
 "featureType": "poi.park",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#9e9e9e"
   }
 ]
},
{
 "featureType": "road",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#ffffff"
   }
 ]
},
{
 "featureType": "road",
 "elementType": "labels.icon",
 "stylers": [
   {
     "color": "#20ae8a"
   }
 ]
},
{
 "featureType": "road.arterial",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#757575"
   }
 ]
},
{
 "featureType": "road.highway",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#dadada"
   }
 ]
},
{
 "featureType": "road.highway",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#616161"
   }
 ]
},
{
 "featureType": "road.local",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#9e9e9e"
   }
 ]
},
{
 "featureType": "transit.line",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#e5e5e5"
   }
 ]
},
{
 "featureType": "transit.station",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#eeeeee"
   }
 ]
},
{
 "featureType": "water",
 "elementType": "geometry",
 "stylers": [
   {
     "color": "#c9c9c9"
   }
 ]
},
{
 "featureType": "water",
 "elementType": "labels.text.fill",
 "stylers": [
   {
     "color": "#9e9e9e"
   }
 ]
}];
                   if(document.getElementById( "map" )) {
                    var map_properties = {
                        center: center,
                        zoom: 15,
                       //  mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: true,
                        zoomControl: true,
                        scrollwheel: false,
                        styles: styles,
                        disableDoubleClickZoom: true,
                        backgroundColor: "#F7F2F9",
                    };

                    document.getElementById( "map" ).style.height = "100%";
                    var map     = new google.maps.Map(document.getElementById( "map" ), map_properties ),
                        marker  = new google.maps.Marker( {
                            position: { lat: 51.5439227, lng: -0.1383555 },
                            map: map,
                            title: 'F3 Architects',
                            // icon: paddingtonGardensTheme.templateURI + '/assets/media/icons/pin.png',
                        }),
                        center  = map.getCenter();

                    google.maps.event.addDomListener(window, 'resize', function () {
                        map.setCenter(center);
                    });
                  }
                }

                google.maps.event.addDomListener(window, 'load', initialize);

            </script>


            <?php
    }

}
