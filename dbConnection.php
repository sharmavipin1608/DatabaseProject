<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$con = mysql_connect("localhost", "root", "root");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("TruckBooking", $con);

?>