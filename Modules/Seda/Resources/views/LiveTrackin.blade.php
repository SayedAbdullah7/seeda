<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seda Shard Oder Location Track</title>
</head>
<body>
<div style="text-align: center;font-size: larger;color: #eeeeef; background: #04328e; margin-bottom: 9px;">Seda Shard Oder Location</div>
<div id="map" style="height: 650px;"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD03tmmafIJahLSvVG3xHQQa_7NDrEZ1i8&callback=initMap" async defer></script>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>

<script>
    let map;
    let marker;
    let directionsService;
    let directionsDisplay;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: {{$fromLocation->latitude}}, lng: {{$fromLocation->longitude}} },
            zoom: 16,
        });

        marker = new google.maps.Marker({
            icon: {
                "url":"https://bt-dev-ride.magdsofteg.xyz/Seda/asset/marker.jpg",
                "scaledSize" : {"width":30,"height":30}
            },
            position: { lat: {{$fromLocation->latitude}}, lng: {{$fromLocation->longitude}} }, // Initial marker position
            map: map,
        });
        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();

        directionsDisplay.setMap(map);
        calculateAndDisplayRoute();

        const socket = io('wss://socket.magdsofteg.xyz');

        // Subscribe to the "live_updates" room event
        socket.on('520.users.'+{{$order->user_id}}, function(data) {
            // Handle the received data
            console.log('Received data:');
            console.log(data);
            data = JSON.parse(data);
            if(data.event == "endOrder"){
                location.reload();
            }
            if(data.event == "userLiveTracking"){
                // Update your UI or perform other actions based on the received data
                // Update the marker position with the new location points
                marker.setPosition(new google.maps.LatLng(data.data.lat, data.data.lng));
                map.panTo(new google.maps.LatLng(data.data.lat, data.data.lng));
            }
        });
    }

    function calculateAndDisplayRoute() {
        waypoints = [];
        @forEach($Locations as $Location)
            waypoints.push({ location: new google.maps.LatLng({{$Location['latitude']}},{{$Location['longitude']}} ) });
        @endforeach

        const request = {
            origin: waypoints[0].location,
            destination: waypoints[waypoints.length - 1].location,
            waypoints: waypoints.slice(1, -1),
            travelMode: google.maps.TravelMode.DRIVING, // You can change the travel mode (DRIVING, WALKING, BICYCLING, or TRANSIT)
        };

        directionsService.route(request, function (result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            } else {
                console.error('Error fetching directions:', status);
            }
        });
    }
</script>
</body>
</html>
