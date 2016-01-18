<?php
    require_once 'dbConnection.php';
    
    session_start();

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
    
    mysql_query($query1);
    mysql_query($query2);
    
    $header = '';
    $data ='';

    $export = mysql_query($query3);

    // extract the field names for header 
    while ($fieldinfo=mysql_fetch_field($export))
    {
            $header .= $fieldinfo->name."\t";
    }
    //echo $header;
    
    // export data 
    while( $row = mysql_fetch_row( $export ) )
    {
        $line = '';
        foreach( $row as $value )
        {                                            
            if ( ( !isset( $value ) ) || ( $value == "" ) )
            {
                $value = "\t";
            }
            else
            {
                $value = str_replace( '"' , '""' , $value );
                $value = '"' . $value . '"' . "\t";
            }
            $line .= $value;
        }
        $data .= trim( $line ) . "\n";
    }
    $data = str_replace( "\r" , "" , $data );

    if ( $data == "" )
    {
        $data = "\nNo Record(s) Found!\n";                        
    }

    $fileName = login.time();

    // allow exported file to download forcefully
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$fileName.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    print "$header\n$data";
 
?>