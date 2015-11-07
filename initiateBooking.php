<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    session_start();
    
    require_once('dbConnection.php');
    
    $query = "select * from location order by driverId;";
//    echo "<br>";
//    echo $query;
//    echo "<br>";
    $locationList = mysql_query($query);
    $count = 0;
    
    $location = array();
//    $location = mysql_fetch_array($locationList);
    
    while ($row = mysql_fetch_array($locationList)) {
        $location[] = $row;
//        echo $row;
    }
    
//    echo sizeof($location);
//    echo "<br>";
//    echo "vipin";
    
//    foreach ($location as $row){
//        if($count != 0)
//            $locationArray = ",";
//        
//        echo $row['latitude'];
//        echo "<br>";
//        echo $row['longitude'];
//        echo "<br>";
//        
//        $locationArray = $row['latitude'] . "," . $row['longitude'];
//        echo "<br> Line : " . $locationArray;
//        
//        $count++;
//    }
//    
//    echo "out of array";
//    echo $locationArray;
    
    $_SESSION['locations'] = $location;
    header("Location:requestTruckPage.php");
?>

