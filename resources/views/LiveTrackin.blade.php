<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div style="text-align: center;font-size: larger;color: #eeeeef; background: #04328e; margin-bottom: 9px;">Seda Shard Oder Location</div>
<div id="map" style="height: 650px;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD03tmmafIJahLSvVG3xHQQa_7NDrEZ1i8&callback=initMap" async defer></script>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>

<script>
    let map;
    let marker;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 30.056132151542908, lng: 31.35886561061008 },
            zoom: 15,
        });

        marker = new google.maps.Marker({
            position: { lat: 30.056132151542908, lng: 31.35886561061008 }, // Initial marker position
            map: map,
        });

        const line = new google.maps.Polyline({
            path: [{"lat":30.05600525144032, "lng":31.35862125307096},{"lat":30.058225979754415, "lng":31.379245029372385}], // Array to store the points of the line
            geodesic: true,
            strokeColor: '#070707',
            strokeOpacity: 1.0,
            strokeWeight: 8,
            map: map,
        });

        const socket = io('wss://socket.magdsofteg.xyz');

        // Subscribe to the "live_updates" room event
        socket.on('520.users.35', function(data) {
            // Handle the received data
            console.log('Received data:');
            console.log(data);
            data = JSON.parse(data);
            // Update your UI or perform other actions based on the received data
            marker.setPosition(new google.maps.LatLng(data.data.lat, data.data.lng));
            map.panTo(new google.maps.LatLng(data.data.lat, data.data.lng));
        });
            // Update the marker position with the new location points
    }
</script>
</body>
</html>
