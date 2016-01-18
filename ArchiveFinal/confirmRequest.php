<?php

    session_start();
    
    $userId = $_SESSION['userId'];
    
    require_once 'dbConnection.php';
    
    $maxTripId = mysql_query("SELECT max(tripId) as tripId FROM trip;");
    
    $driverQuery = "select driverId from driver order by driverId;";
    $driverList = mysql_query($driverQuery);
    $count = 0;
    $driverArray = array();
    while ($row1 = mysql_fetch_array($driverList)) {
        $driverArray[$count] = $row1['driverId'];
//        echo $driverArray[$count];
        $count++;
    }
    //echo "<br><br> size of driver : ".$driverArray;

    while ($row = mysql_fetch_array($maxTripId))
        $tripId = $row['tripId'] + 1;
    
    if($tripId == 1)
        $tripId = 1001;
        
//    echo $_POST['timeToDest'];
    
//    echo "<br><br> trip id : ".$tripId;
    
//    echo "<br>".  sizeof($_POST['timeToDest']);
    
    $array = explode(",",$_POST['timeToDest']);
    //echo $array;
    
    $distToDest = $array[sizeof($array) - 2];
    //echo "<br><br> dist to dest : ".$distToDest;
    
    $destAddress = $_POST['destAddress'];
    
    $createTripQuery = "insert into trip(tripId,userId,rateId,"
            . "distance,status,destAddress,startTime) values "
            . "($tripId,'$userId',(select rateId from rate where status = 'A'),"
            . "$distToDest,'I','$destAddress',CURRENT_TIMESTAMP);";
    //echo "<br>createTripQuery : ".$createTripQuery;
    mysql_query($createTripQuery);
    
    
    //echo "<br><br><br>";
    
//    echo "<br><br>".  sizeof($array);
    
    for($i = 0; $i < ((sizeof($array)-2)/2); $i++) {
        if( $i == 0 ){
            $distance = $array[$i];
            $time = $array[$i+1];
        }
        else{
            $distance = $array[$i*2];
            $time = $array[$i*2+1];
        }
        $driver = $driverArray[$i];
        
        $tripDump = "insert into tripDump values ($tripId,'$driver',$distance,$time);";
        mysql_query($tripDump);
        
//        echo "<br>tripDump : ".$tripDump;
//        
//        
//        echo "<br> driver : ".$driver;
//        echo "<br> distance : ".$distance;
//        echo "<br> time : ".$time;
    }
    
    $limitQuery = "select ceil(count(*)/2) as limitDrivers from driver;";
        $limitQueryResult = mysql_query($limitQuery);
        $limitQueryArray = mysql_fetch_array($limitQueryResult);
        $limit = $limitQueryArray['limitDrivers'];
        
//        echo $limitQuery;
//        echo "<br>limit ".$limit;
        
        $tempTableQuery = "create table temp$tripId (driverId varchar(12));";
        mysql_query($tempTableQuery);
//        echo "$tempTableQuery";
        
        $insertIntoTemp = "insert into temp$tripId (driverId) (select driverId from "
                . "tripDump where tripId = $tripId order by distanceToClient "
                . "limit 3) union (select driverId from tripDump where "
                . "tripId = $tripId order by timeToClient limit 3);";
        mysql_query($insertIntoTemp);
//        echo "$insertIntoTemp";
        
        $tripDumpSorting = "delete from tripDump where driverId not in (select * from temp$tripId);";
        mysql_query($tripDumpSorting);
//        echo $tripDumpSorting;
        
        mysql_query("drop table temp$tripId;");
        
        header("Location:customerHomePage.php");
    
?>

