<!DOCTYPE html>
<html>
    <head>
        <style>
            html, body, #map {
                width: 100%;
                height: 100%;
            }

            .overlay {
                display: none;
                z-index: 2;
                background: #000;
                position: fixed;
                width: 100%;
                height: 100%;
                top: 0px;
                left: 0px;
                text-align: center;
            }
            .specialBox {
                display: none;
                position: relative;
                z-index: 3;
                margin: 150px auto 0px auto;
                width: 500px; 
                height: 300px;
                background: #FFF;
                color: #000;
                font-family: monospace, monospace;
                font-size: 16px;
            }

            .wrapper {
                position:absolute;
                top: 0px;
                left: 0px;
                padding-left:24px;
            }
        </style>

        <script src="https://maps.googleapis.com/maps/api/js"></script>

        <script>
            var distanceMatrixResult = [];
            //array containing truck positions
            var truckLocations = [];

            var arr = "";
            
            <?php
            session_start();
            
            $location = $_SESSION['locations'];
            
            foreach ($location as $row){
            ?>
                
                arr += "<?php echo $row['latitude']?>,";
                arr += "<?php echo $row['longitude']?>,";
                
            <?php
            }
            ?>
                
            var originsT = [];
            var destinationsT = [];
            var service = new google.maps.DistanceMatrixService();
            var click = false;
            
            var query = {
                origins: originsT,
                destinations: destinationsT,
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.IMPERIAL
            };

            //variable to store map object
            var map;

            //variable to store current lat and long of the customer
            var currentLatlng;

            //variable to store marker at the location clicked by the user
            var locationChosenMarker = null;

            //initializing info window
            var infowindow = new google.maps.InfoWindow(
                    {
                        size: new google.maps.Size(225, 75)
                    });

            //save destination address
            var destinationAddress;

            //call initializeMap on page load
            google.maps.event.addDomListener(window, 'load', initializeMap);

            function initializeMap() {

                //alert(document.getElementById("trial").value);
                
                
                truckLocations = arr.split(",");
                truckLocations.pop();
                
                //alert("map initialised" + truckLocations.length);

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(success, error);
                } else {
                    alert('geolocation not supported');
                }
            }

            //If there is error in getting the current location
            function error(msg) {
                alert('error: ' + msg);
            }

            //If current location is located 
            function success(position) {
                //alert("geolocation successful")

                //alert(position.coords.latitude + " " + position.coords.longitude)

                //get the current latitude and logitude 
                currentLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                var mapOptions = {
                    zoom: 14,
                    center: currentLatlng,
                    scaleControl: true,
                    draggable: true,
                    scrollwheel: true,
                    navigationControl: true,
                    mapTypeControl: true,
                    streetViewControl: true
                }

                map = new google.maps.Map(document.getElementById('map'), mapOptions);

                //mark current location of the user
                //alert("marking current location");
                markLocation(position.coords.latitude, position.coords.longitude, null);

                //add event listeners
                addListeners();

                //populate origins and destinations
                populateData();

                //mark the locations of the trucks
                markTrucks();

                var centerControlDiv = document.createElement('div');
                var centerControl = new CenterControl(centerControlDiv, map);

                centerControlDiv.index = 1;
                map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);
            }

            function CenterControl(controlDiv, map) {

                // Set CSS for the control border.
                var controlUI = document.createElement('div');
                controlUI.style.backgroundColor = '#fff';
                controlUI.style.border = '2px solid #fff';
                controlUI.style.borderRadius = '3px';
                controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
                controlUI.style.cursor = 'pointer';
                controlUI.style.marginBottom = '22px';
                controlUI.style.marginTop = '12px';
                controlUI.style.marginRight = '42px';
                controlUI.style.textAlign = 'center';
                controlUI.title = 'Click to recenter the map';
                controlDiv.appendChild(controlUI);

                // Set CSS for the control interior.
                var controlText = document.createElement('div');
                controlText.style.color = 'rgb(25,25,25)';
                controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
                controlText.style.fontSize = '16px';
                controlText.style.lineHeight = '38px';
                controlText.style.paddingLeft = '5px';
                controlText.style.paddingRight = '5px';
                controlText.innerHTML = 'Request Truck';
                controlUI.appendChild(controlText);

                // Setup the click event listeners: simply set the map to Chicago.
                controlUI.addEventListener('click', function () {
                    //alert("here");
                    toggleOverlay();
                });

            }

            function populateData() {
                //alert("populateData");
                originsT[0] = currentLatlng;
                //alert("dfsdfsdf ");
                var count = 0;
                for (var i = 0; i < truckLocations.length; i += 2) {
                    //alert(i);
                    var latLng = new google.maps.LatLng(truckLocations[i], truckLocations[i + 1]);
                    destinationsT[count] = latLng;
                    count++;
                }
                //alert("exiting function");
            }

            function markLocation(latitude, longitude, image) {
                var latlng = new google.maps.LatLng(latitude, longitude);
                //alert("markCurrentUserLocation");

                var marker = new google.maps.Marker({
                    map: map,
                    position: latlng,
                    icon: image
                });
            }

            function addListeners() {
                //alert("addListeners");
                google.maps.event.addListener(map, 'click', function () {
                    infowindow.close();
                });

                google.maps.event.addListener(map, 'click', function (event) {
                    //alert("inside listener");
                    //call function to create marker
                    if (locationChosenMarker) {
                        locationChosenMarker.setMap(null);
                        locationChosenMarker = null;
                    }

                    //reverse geocoding and create marker at the location user clicks on the map
                    var geocoder = new google.maps.Geocoder;
                    geocoder.geocode({'location': event.latLng}, function (results, status) {
                        //alert("geocode return result");
                        if (status === google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                destinationAddress = results[1].formatted_address;
                                document.getElementById("destAddressDiv").innerHTML = destinationAddress;
                                document.getElementById("destAddress").value = destinationAddress;
                                //alert("address : " + results[1].formatted_address);
                                locationChosenMarker = createMarker(event.latLng, "name", "<b>Location</b><br>" + results[1].formatted_address);
                            }
                            else {
                                locationChosenMarker = createMarker(event.latLng, "name", "<b>Location</b><br>" + event.latLng);
                            }
                        }
                    });

                    if(click){
                        destinationsT.pop();
                    }
                    
                    click = true;
                    destinationsT[destinationsT.length] = event.latLng;

                    getDistanceOfTrucks(33.81, -118.14);

                });
            }

            //Mark the location as user clicks on the map
            function createMarker(latlng, name, html) {
                var contentString = html;

                locationChosenMarker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    zIndex: Math.round(latlng.lat() * -100000) << 5
                });

                google.maps.event.addListener(locationChosenMarker, 'click', function () {
                    infowindow.setContent(contentString);
                    infowindow.open(map, locationChosenMarker);
                });

                google.maps.event.trigger(locationChosenMarker, 'click');
                return locationChosenMarker;
            }

            //mark the trucks on the map
            //TODO: add the label so that trucks can be differentiated
            function markTrucks() {
                var image = "truck-2.png";
                //alert("marking trucks");
                for (var i = 0; i < truckLocations.length; i += 2) {
                    //alert(i + " " + truckLocations[i] + " " + truckLocations[i+1]);
                    markLocation(truckLocations[i], truckLocations[i + 1], image);
                }
            }

            function getDistanceOfTrucks(latitude, longitude) {
                //alert("get disance" + currentLatlng);


                /*alert("truckLocations : " + truckLocations[0] + " " + truckLocations[1]);
                         
                 //var destinationTruck = new google.maps.Latlng(33.81053176901896, -118.14311027526855);
                         
                 var latlng = new google.maps.LatLng(latitude, longitude);
                         
                 var sss = new google.maps.LatLng(33.81, -118.14);
                         
                 alert("destination : " + latitude + " " + longitude);*/

                service.getDistanceMatrix(query, function (response, status) {
                    //alert("distance matrix callback");
                    distanceMatrixResult = [];
                    if (status == google.maps.DistanceMatrixStatus.OK) {
                        var origins = response.originAddresses;
                        var destinations = response.destinationAddresses;
                        //alert("distances returned : " + origins.length + " : " + destinations.length);
                        for (var i = 0; i < origins.length; i++) {
                            var results = response.rows[i].elements;
                            for (var j = 0; j < results.length; j++) {
                                var element = results[j];
                                var distance = Math.round((element.distance.value)*0.000621371*10)/10;
                                var duration = Math.round((element.duration.value)/60);
                                var from = origins[i];
                                var to = destinations[j];
                                distanceMatrixResult.push(distance);
                                distanceMatrixResult.push(duration);
                                //alert("distance from : " + from + " to : " + to + " is : " + distance + " and it will take : " + duration);
                            }
                        }
//                        alert(distanceMatrixResult.length + " " + distanceMatrixResult[distanceMatrixResult.length-2]
//                                + " " + distanceMatrixResult[distanceMatrixResult.length-1]);
                        document.getElementById('timeToDest').value=distanceMatrixResult;
                        //document.getElementById('distToDest').value=distanceMatrixResult[distanceMatrixResult.length-1];
                        
                        document.getElementById('timeToDestDiv').innerHTML = distanceMatrixResult[distanceMatrixResult.length-1] + " mins";
                        document.getElementById('distToDestDiv').innerHTML = distanceMatrixResult[distanceMatrixResult.length-2] + " mi";
                    }
                });


            }

            
        </script>
        <script>
            function toggleOverlay() {
                //alert("toggleOverlay()")
                var overlay = document.getElementById('overlay');
                var specialBox = document.getElementById('specialBox');
                overlay.style.opacity = .8;
                if (overlay.style.display == "block") {
                    overlay.style.display = "none";
                    specialBox.style.display = "none";
                } else {
                    overlay.style.display = "block";
                    specialBox.style.display = "block";
                }
            }
        </script>
    </head>

    <body>
        <!--<input type="hidden" value="vipin sharma" id="trial"/>-->
        <div id = "map" class="wrapper"></div>

        <div id="overlay" class="overlay"></div>
        <!-- End Overlay -->
        <!-- Start Special Centered Box -->
        <div id="specialBox" class="specialBox" align="center">
            <h1>Request Details</h1>
            <form id="confirmRequest" method="POST" name="confirmRequest" action="confirmRequest.php">
                <table>
                    <tr>
                        <td>
                            Destination : 
                        </td>
                        <td>
                            <div id="destAddressDiv"></div>
                            <input type="hidden" name="destAddress" id="destAddress"/>
                        </td>
                    </tr>
                    <br>
                    <tr>
                        <td>
                            Expected Time : 
                        </td>
                        <td>
                            <div id="timeToDestDiv"></div>
                            <input type="hidden" name="timeToDest" id="timeToDest"/>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            Destination : 
                        </td>
                        <td>
                            <div id="distToDestDiv"></div>
                            <!--<input type="hidden" name="distToDest" id="distToDest"/>-->
                        </td>
                    </tr>
                    <br>
                </table>
                
                <br><br>
                <button onmousedown="toggleOverlay()">Back to Map</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit">Submit</button>
            </form>
        </div>
    </body>

</html>