<?php
    session_start();
    require_once 'dbConnection.php';
    $purpose = $_GET['purpose'];
    $tripId = $_GET['tripId'];
    
    if($purpose == "withdrawTrip"){
        $withdrawTripQuery = "update trip set status = 'W' where tripId = $tripId";
        mysql_query($withdrawTripQuery);
        
        $clearTripDump = "delete from tripDump where tripId = $tripId";
        mysql_query($clearTripDump);
    }
    
    elseif ($purpose == "refresh") {
        $driverDetailsQuery = "select driverId from trip where tripId = $tripId and driverId is not null;";
        //$returnText = $driverDetailsQuery;
        $result = mysql_query($driverDetailsQuery);
        $driverDetails = mysql_fetch_array($result);
        //$returnText.="<br>size : ".sizeof($driverDetails);
        if(sizeof($driverDetails) > 1){
            $driverId = $driverDetails['driverId'];
            $returnText = "Driver <b>$driverId</b> will pick you up.";
        }
        else{
            $returnText = "return";
        }
        echo $returnText;
    }
    
    elseif ($purpose == "rejectRequest") {
        $userId = $_SESSION['userId'];
        $rejectRequestQuery = "delete from tripDump where tripId = $tripId and"
                . " driverId = '$userId';";
        mysql_query($rejectRequestQuery);
        
        $openTripsQuery = "select A.tripId as tripId, A.distanceToClient as distanceToClient, "
            . "A.timeToClient as timeToClient, B.destAddress as destAddress"
            . " from tripDump A, trip B where A.tripId = B.tripId and A.driverId = '$userId';";
    
        $openTripsResult = mysql_query($openTripsQuery);
        $tripsArray = array();

        while($row = mysql_fetch_array($openTripsResult))
                $tripsArray[] = $row;

        $size = sizeof($tripsArray);
        if($size >= 1){
            $resultText='<table class="table"><tr><th>Trip Id</th><th>Address</th>'
                    . '<th>Distance to Client</th><th>Time To Client</th><th>'
                    . '</th></tr>';

            foreach($tripsArray as $row){
                $resultText.='<tr id="'.$row[tripId].'" name="trips" >'
                        . '<td>'.$row[tripId].'</td>'
                        . '<td>'.$row[destAddress].'</td>'
                        . '<td>'.$row[distanceToClient].'</td>'
                        . '<td>'.$row[timeToClient].'</td>'
                        . '<td><button name="reject" onclick="rejectRequest('.$row[tripId].')" class="btn btn-danger">Reject</button>'
                        . '<button name="accept" onclick="acceptRequest('.$row[tripId].')" class="btn btn-success">Accept</button></td>'
                        . '</tr>';
            }
            $resultText.='</table>';
        }
        else {
            $resultText='No rides as of yet.';
        }
        echo $resultText;
    }
    
    elseif ($purpose == "acceptRequest") {
        $userId = $_SESSION['userId'];
        
        $acceptTripQuery = "update trip set driverId = '$userId', startTime = "
                . "CURRENT_TIMESTAMP, status = 'T';";
        //echo $acceptTripQuery;
        mysql_query($acceptTripQuery);
        
        $openTripsQuery = "select A.tripId as tripId, A.distanceToClient as distanceToClient, "
            . "A.timeToClient as timeToClient, B.destAddress as destAddress"
            . " from tripDump A, trip B where A.tripId = B.tripId and A.tripId = $tripId and "
            . "A.driverId = '$userId';";
        $openTripsResult = mysql_query($openTripsQuery);
        //echo $openTripsQuery;
        
        $tripsArray = array();

        while($row = mysql_fetch_array($openTripsResult))
                $tripsArray[] = $row;

        $size = sizeof($tripsArray);
        //echo $size;
        
        if($size >= 1){
            $resultText='<table class="table"><tr><th>Trip Id</th><th>Address</th>'
                    . '<th>Distance to Client</th><th>Time To Client</th><th>'
                    . '</th></tr>';

            foreach($tripsArray as $row){
                $resultText.='<tr id="'.$row[tripId].'" name="trips" >'
                        . '<td>'.$row[tripId].'</td>'
                        . '<td>'.$row[destAddress].'</td>'
                        . '<td>'.$row[distanceToClient].'</td>'
                        . '<td>'.$row[timeToClient].'</td>'
                        . '<td><button name="complete" onclick="completeTrip('.$row[tripId].')" class="btn btn-success">Complete Trip</button>'
                        . '</td></tr>';
            }
            $resultText.='</table>';
        }
        else {
            $resultText='No rides as of yet.';
        }
        $acceptRequestQuery = "delete from tripDump where tripId = $tripId "
                . "and driverId not in ('$userId');";
        mysql_query($acceptRequestQuery);
        //echo $acceptRequestQuery;
        
        echo $resultText;
    }
    
    elseif ($purpose == "completeTrip") {
        $completeTripQuery = "update trip set endTime = CURRENT_TIMESTAMP, status = 'C'"
                . "where tripId = $tripId;";
        //$returnText = $driverDetailsQuery;
        mysql_query($completeTripQuery);
        
        $returnText = $tripId.' completed. Recipt is generated and available '
                . 'under recipts tab.<br>Refresh the page to search for new trips.';
        
        $deleteRecordQuery = "delete from tripDump where tripId = ".$tripId;
        mysql_query($deleteRecordQuery);
        
        //calculate payment
        $getApplicableRateQuery = "select CEIL((endtime - starttime)/100) as timeTravelled "
                . ", distance, pricePerMinute, PricePerMile, basePrice from trip, rate where trip.tripId = $tripId and "
                . "rate.rateId = trip.rateId;";
        $getApplicableRateResult = mysql_query($getApplicableRateQuery);
        $getApplicableRate = mysql_fetch_array($getApplicableRateResult);
        echo $getApplicableRateQuery;
        
        echo $getApplicableRate['timeTravelled']."->".$getApplicableRate['pricePerMinute'];
        echo $getApplicableRate['distance']."->".$getApplicableRate['PricePerMile'];
        echo $getApplicableRate['basePrice'];
        
        $total = $getApplicableRate['basePrice'] + ($getApplicableRate['distance'] * $getApplicableRate['PricePerMile'])
                + ($getApplicableRate['timeTravelled'] * $getApplicableRate['pricePerMinute']);
        
        echo $total;
        
        $registerPaymentQuery = "insert into payment(tripId,amount) values($tripId,$total);";
        echo $registerPaymentQuery;
        mysql_query($registerPaymentQuery);
        
        echo $returnText;
    }
    
    else if($purpose == "updateLocation"){
        $driverId = $_GET['driverId'];
        $latitude = trim($_GET['latitude']);
        $longitude = trim($_GET['longitude']);
        
        $query = "update location set latitude = $latitude,longitude = $longitude "
                . " where driverId = '$driverId'";
        mysql_query($query);
        echo $query;
    }
    else if($purpose == "verifyUserName"){
        $userId = $_GET['userId'];
        $query = "select * from customer where userId = '$userId'";
        $resultQuery = mysql_query($query);
        
        //$resultArray = [];
        
        while($row = mysql_fetch_array($resultQuery)){
            $resultArray[] = $row;
        }
        
        if(sizeof($resultArray) > 0){
            $responseText = "failure";
        }
        else{
            $responseText = "success";
        }
        echo $responseText;
    }
?>
    