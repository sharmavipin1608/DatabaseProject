<?php

    session_start();
    
    require_once 'dbConnection.php';
    
    $userId = $_SESSION['userId'];
    
    $query1 = "create temporary table temp$userId as ( select tripId,userId,driverId,"
            . "date(startTime) as tripDate,destAddress,statusString from trip A"
            . ",status B where A.userId = '$userId' and A.status = B.status order by startTime DESC);";
    $query2 = "create temporary table temp1$userId as (select A.tripId, A.userId, "
            . "A.driverId, A.tripDate, A.destAddress, A.statusString , "
            . "B.lastName, B.firstName from temp$userId A left outer join "
            . "customer B on A.driverId = B.userId);";
    $query3 = "select A.tripId, A.userId, A.driverId, A.tripDate, A.destAddress, "
            . "A.statusString , A.lastName, A.firstName ,B.amount from temp1$userId A "
            . "left outer join payment B on A.tripId = B.tripId;";
    //echo $query1."<br>".$query2."<br>".$query3;
    
    mysql_query($query1);
    mysql_query($query2);
    
//    $tripHistoryQuery = "select A.lastName, A.firstName, B.destAddress, "
//            . "date(B.startTime) as tripDate, C.amount, D.statusString "
//            . "from customer A, trip B, payment C, status D where "
//            . "A.userId = B.driverId and B.tripId = C.tripId and "
//            . "B.status = D.status order by B.startTime DESC;";
    $tripHistoryResult = mysql_query($query3);
    $tripHistory = array();
    
    while($row = mysql_fetch_array($tripHistoryResult))
            $tripHistory[] = $row;
    //echo sizeof($tripHistory);

?>
<html>
    <head>
        <script src="jquery.min.js"></script>
        <script src="bootstrap.min.js"></script>
        <script>
            function exportDataAsAscii(formId)
            {
                alert("exportDataAsAscii");
                document.forms[formId].action = "exportDataAsASCII.php";
                document.forms[formId].submit();
            }
        </script>
    </head>
    
    <body>
        <br><br>
        <div class="panel-group" id='panel-group-1'>
        <div class="panel panel-primary">
            <div class="panel-heading">
            <a class="panel-title" data-toggle="collapse" data-parent="#panel-group-1" href="#panel-element">Trip History</a>
            </div>
            <div id="panel-element" class="panel-collapse collapse in">
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
                            <th>Amount
                            <th>Status</th>
                        </tr>
                    <?php    
                        foreach($tripHistory as $row){
                    ?> 
                            <tr>
                                <td><?php if($row['lastName'] == NULL){echo "-";}else{echo $row['lastName'].",".$row['firstName'];} ?></td>
                                <td><?php echo $row['destAddress']; ?></td>
                                <td><?php echo $row['tripDate']; ?></td>
                                <td><?php if($row['amount'] == NULL){echo "0";}else{echo $row['amount']." $";} ?></td>
                                <td><?php echo $row['statusString']; ?></td>
                            </tr>
                    <?php    
                        }
                    ?>     
            <?php
                }
            ?>
                <div align="right">
                    <form id='exportForm' method='POST'>
                        <input type='hidden' name='query' value='<?php echo $query3; ?>'/>
                        <button name="downloadExcel" id="downloadExcel" onclick="exportDataAsAscii(this.form.id)" class="btn btn-primary">Export as Excel</button>
                        <button name="downloadXML" id="downloadXML" onclick="openMaps()" class="btn btn-primary">Export as XML</button>
                    </form>
                    
                </div>
                 <br>           
            </div>
                
            </div>
        </div>
        </div>
    </body>
</html>