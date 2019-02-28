<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

    </style>
</head>
<body>
<div id="floating-panel">
    <b>Start: </b>
    <select id="start" onchange="calcRoute();">
        <option value="华盛达广场, 杭州">华盛达广场, 杭州</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
    </select>
    <b>End: </b>
    <select id="end" onchange="calcRoute();">
        <option value="西湖文化广场, 杭州">西湖文化广场, 杭州</option>
        <option value="st louis, mo">St Louis</option>
        <option value="joplin, mo">Joplin, MO</option>
        <option value="oklahoma city, ok">Oklahoma City</option>
        <option value="amarillo, tx">Amarillo</option>
        <option value="gallup, nm">Gallup, NM</option>
        <option value="flagstaff, az">Flagstaff, AZ</option>
        <option value="winona, az">Winona</option>
        <option value="kingman, az">Kingman</option>
        <option value="barstow, ca">Barstow</option>
        <option value="san bernardino, ca">San Bernardino</option>
        <option value="los angeles, ca">Los Angeles</option>
    </select>
</div>
<div id="map"></div>
<script>
    function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: {lat: 41.85, lng: -87.65}
        });
        directionsDisplay.setMap(map);

        var onChangeHandler = function() {
            calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
            origin: document.getElementById('start').value,
            destination: document.getElementById('end').value,
            travelMode: google.maps.TravelMode.DRIVING,
            provideRouteAlternatives:true
        }, function(response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }

</script>
<script src="http://maps.google.cn/maps/api/js?key=AIzaSyD_MW2d9ttF1T8_xFbVkzjiaQwWiPFrvc8&callback=initMap"
        async defer></script>
</body>
</html>