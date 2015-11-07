<?php

    require_once 'dbConnection.php';
    $tableName = $_POST['tableList'];
    $sql = "SELECT * FROM $tableName;";
    $result = mysql_query($sql);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }

    //echo mysql_num_rows($result);
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"."\n";
    $xml.= "<$tableName>"."\n";
    if(mysql_num_rows($result)>0)
    {
       while($result_array = mysql_fetch_assoc($result))
       {
          $xml .= "\t"."<RECORD>";

          //loop through each key,value pair in row
          foreach($result_array as $key => $value)
          {
             //$key holds the table column name
             //echo $key." ".$value;
             $xml .= "\n\t\t"."<$key>";

             //embed the SQL data in a CDATA element to avoid XML entity issues
             $xml .= "$value";

             //and close the element
             $xml .= "</$key>";
          }

          $xml.="\n\t"."</RECORD>"."\n";
       }
    }
    $xml.= "</$tableName>";

    $fileName = $tableName.time();

    header("Content-Type: text/xml");
    header("Content-Disposition: attachment; filename=$fileName.xml");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $xml;

?>