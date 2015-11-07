<?php
require_once('header.php');

require_once('dbConnection.php');

session_start();

//echo "driverPage";
$ongoingTrip = false;

//$disabled = true;
//$tripInitiated = $_GET['tripInitiated'];
//$tripId = $_GET['tripId'];

$userId = $_SESSION['userId'];

$openTripsQuery = "select A.tripId as tripId, A.distanceToClient as distanceToClient, "
        . "A.timeToClient as timeToClient, B.destAddress as destAddress"
        . " from tripDump A, trip B where A.tripId = B.tripId and A.driverId = '$userId' ";

$ongoingTripsQuery = "select tripId from trip where driverId = '$userId' and status = 'T'";
$ongoingTripsResult = mysql_query($ongoingTripsQuery);
$ongoingTrips = mysql_fetch_array($ongoingTripsResult);

//echo "ongoingTrips = " . $ongoingTrip;

if (sizeof($ongoingTrips) > 1) {
    $tripId = $ongoingTrips['tripId'];
    $openTripsQuery.=" and A.tripId = $tripId;";
    $ongoingTrip = true;
} else {
    $openTripsQuery.="and B.status = 'I';";
}
$openTripsResult = mysql_query($openTripsQuery);

//echo $openTripsQuery;

$tripsArray = array();

while ($row = mysql_fetch_array($openTripsResult))
    $tripsArray[] = $row;

$size = sizeof($tripsArray);

//echo "size =" . $size;
?>

<html>
    <head>
        <link href="bootstrap.css" rel="stylesheet">
        <script src="jquery.min.js"></script>
        <script src="bootstrap.min.js"></script>
        <style>
            .body1 {

                background-image: url("blue.jpg");

            }
        </style>
        <script>
//            function openMaps(){
//                //alert("opening maps");
//                window.location.href = "initiateBooking.php";
//            }
//            
            function rejectRequest(tripId) {
//                alert(tripId + document.getElementById(tripId).innerHTML);
//                alert(document.getElementsByName("trips").length);
                var ajaxRequest = new XMLHttpRequest();

                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState == 4) {
                        //var ajaxDisplay = document.getElementById('release');
//                        alert(ajaxRequest.responseText);
                        document.getElementById('availableTrips').innerHTML = ajaxRequest.responseText;

                    }
                }

                ajaxRequest.open("GET", "queriesRepository.php?purpose=rejectRequest&tripId=" + tripId, true);
                ajaxRequest.send(null);
                document.getElementById(tripId).style.display = "none";


            }

            function acceptRequest(tripId) {
//                alert("acceptRequest");
                var ajaxRequest = new XMLHttpRequest();

                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState == 4) {
//                        alert(ajaxRequest.responseText);
                        document.getElementById('availableTrips').innerHTML = ajaxRequest.responseText;
                    }
                }

                ajaxRequest.open("GET", "queriesRepository.php?purpose=acceptRequest&tripId=" + tripId, true);
                ajaxRequest.send(null);
//                alert("refreshed");

            }

            function completeTrip(tripId) {
//                alert("completeTrip");
                var ajaxRequest = new XMLHttpRequest();

                ajaxRequest.onreadystatechange = function () {
                    if (ajaxRequest.readyState == 4) {
//                        alert(ajaxRequest.responseText);
                        document.getElementById('availableTrips').innerHTML = ajaxRequest.responseText;
                    }
                }

                ajaxRequest.open("GET", "queriesRepository.php?purpose=completeTrip&tripId=" + tripId, true);
                ajaxRequest.send(null);
//                alert("refreshed");

            }
        </script>
    </head>
    <body style="font-family:verdana;">
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading"><b>Trip details</b></div>
                <div id="availableTrips"> 
<?php
if ($size >= 1) {
    ?>
                        


                        <table class="table">
                            <tr>
                                <th>Trip Id</th>
                                <th>Address</th>
                                <th>Distance to Client</th>
                                <th>Time To Client</th>
                                <th></th>
                            </tr>
    <?php
    foreach ($tripsArray as $row) {
        ?>

                                <tr id="<?php echo $row[tripId] ?>" name="trips" >
                                    <td><?php echo $row[tripId] ?></td>
                                    <td><?php echo $row[destAddress] ?></td>
                                    <td><?php echo $row[distanceToClient] ?></td>
                                    <td><?php echo $row[timeToClient] ?></td>
                                    <td>
        <?php
        if ($ongoingTrip == true) {
            ?>
                                            <button name="complete" onclick="completeTrip(<?php echo $row[tripId] ?>)" class="btn btn-success">Complete Trip</button>
                                            <?php
                                        } else {
                                            ?>
                                            <button name="reject" onclick="rejectRequest(<?php echo $row[tripId] ?>)" class="btn btn-danger">Reject</button>
                                            <button name="accept" onclick="acceptRequest(<?php echo $row[tripId] ?>)" class="btn btn-success">Accept</button>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>


    <?php } ?>
                        </table>

                            <?php
                        } else {
                            ?>


                        No rides as of yet.
                        <?php
                    }
                    ?>
                </div>
            </div>
            
<?php require_once 'driverTripHistory.php'; ?>


        </div>

        


    </body>
</html>