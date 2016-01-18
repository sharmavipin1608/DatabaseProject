<?php

    session_start();
    
    require_once 'dbConnection.php';
    
    $userId = $_SESSION['userId'];
    
    $query1 = "select A.lastName, A.firstName, B.destAddress, date(B.startTime) "
            . "as tripDate, C.amount, "
            . "CEIL((endtime - starttime)/100) as "
            . "travelTime, B.distance from customer A, trip B, "
            . "payment C where A.userId = B.userId and "
            . "B.tripId = C.tripId and "
            . "B.driverId = '$userId' and B.status = 'C' order by B.startTime DESC;";
    
    $tripHistoryResult = mysql_query($query1);
    $tripHistory = array();
    
    while($row = mysql_fetch_array($tripHistoryResult))
            $tripHistory[] = $row;
    //echo sizeof($tripHistory);

?>
<html>
    <head>
        
    </head>
    
    <body>
        <br><br>
        <div class="panel panel-primary">
            <div class="panel-heading">Trip History</div>
            <div class="panel-body" id="tripDetails">
            <?php
                if(sizeof($tripHistory) == 0){
            ?>
                    You haven't used our service yet.
            <?php
                }
                else{
            ?>
                    <table class="table">
                        <tr>
                            <th>Driver Name</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Travel Time</th>
                            <th>Distance</th>
                            <th>Amount
                            
                        </tr>
                    <?php    
                        foreach($tripHistory as $row){
                    ?> 
                            <tr>
                                <td><?php echo $row['lastName'].",".$row['firstName']; ?></td>
                                <td><?php echo $row['destAddress']; ?></td>
                                <td><?php echo $row['tripDate']; ?></td>
                                <td><?php echo $row['travelTime']." mins"; ?></td>
                                <td><?php echo $row['distance']." mi"; ?></td>
                                <td><?php echo $row['amount']." $"; ?></td>
                            </tr>
                    <?php    
                        }
                    ?>     
            <?php
                }
            ?>
            </div>
        </div>
    </body>
</html>