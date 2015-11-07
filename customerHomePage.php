<?php
require_once('header.php');

require_once('dbConnection.php');

require_once 'authorizeUser.php';

session_start();

$userId = $_SESSION['userId'];

$openTripsQuery = "select tripId,driverId,status from trip where userId = '$userId' and status in ('T','I');";

$openTripResult = mysql_query($openTripsQuery);

$openTrip = mysql_fetch_array($openTripResult);

$enableRequestButton = true;

if (sizeof($openTrip) > 1) {
    $status = $openTrip['status'];
    $tripId = $openTrip['tripId'];
    $enableRequestButton = false;

    if ($status == 'I') {
        $tripInitiated = true;
    } else {
        $tripResume = true;
        $driverIdDetails = $openTrip['driverId'];
    }
}
?>

<html>
    <head>
        <link href="bootstrap.css" rel="stylesheet">
        
        <script>
            function openMaps() {
                //alert("opening maps");
                window.location.href = "initiateBooking.php";
            }

            function withdrawRequest(tripId) {
                var ajaxRequest = new XMLHttpRequest();

                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState == 4) {
                        //var ajaxDisplay = document.getElementById('release');
                        document.getElementById('tripDetails').innerHTML = "Request withdrawn successfully";
                    }
                }

                ajaxRequest.open("GET", "queriesRepository.php?purpose=withdrawTrip&tripId=<?php echo $tripId ?>", true);
                ajaxRequest.send(null);


            }

            function refresh() {
                //alert("refresh");
                var ajaxRequest = new XMLHttpRequest();

                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState == 4) {
                        //alert(ajaxRequest.responseText);
                        if (ajaxRequest.responseText.indexOf("return") != -1) {
                            //alert("returning ");
                            return;
                        }
                        else {
                            document.getElementById('tripDetails').innerHTML = ajaxRequest.responseText;
                        }
                    }
                }

                ajaxRequest.open("GET", "queriesRepository.php?purpose=refresh&tripId=<?php echo $tripId ?>", true);
                ajaxRequest.send(null);
                //alert("refreshed");

            }
        </script>
    </head>
    <body style="font-family:verdana;">
        <div class="container-fluid">
            
            <?php
            if($enableRequestButton == true){
            ?>
                <div align="center">
                    <button name="requestTruck" id="intitateBooking" value="RequestTruck" onclick="openMaps()" class="btn btn-primary">RequestTruck</button>
                </div>
            <?php
            }
            ?>
            

<?php
if ($tripInitiated == true) {
    ?>
                <!--        <fieldset>
                            <legend>Trip details</legend>
                            <div align="left" id="tripDetails">
                                Waiting for driver to accept the trip.
                                <button name="withdraw" onclick="withdrawRequest(<?php echo $tripId ?>)" class="btn btn-danger">Withdraw</button>
                                <button name="refresh" onclick="refresh()" class="btn btn-success">Refresh</button>
                            </div>
                        </fieldset>-->
                </br></br>
                <div class="panel panel-primary">
                    <div class="panel-heading">Trip details</div>
                    <div class="panel-body" id="tripDetails">
                        Waiting for driver to accept the trip.
                        <button name="withdraw" onclick="withdrawRequest(<?php echo $tripId ?>)" class="btn btn-danger">Withdraw</button>
                        <button name="refresh" onclick="refresh()" class="btn btn-success">Refresh</button>
                    </div>
                </div>
    <?php
} else if ($tripResume == true) {
    ?>
                </br></br>

                <div class="panel panel-primary">
                    <div class="panel-heading">Trip details</div>
                    <div class="panel-body" id="tripDetails">
                        Driver <b><?php echo $driverIdDetails ?></b> will pick you up.
                    </div>
                </div>
    <?php
}
?>

            <div id="tripHistory">
            <?php require_once 'customerTripHistory.php'; ?>
            </div>
        </div>


    </body>
</html>