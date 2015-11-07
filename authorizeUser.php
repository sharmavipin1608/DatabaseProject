<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
                    
if(!isset($_SESSION['userId']))
{
    //echo "nothing set";
    header("Location:loginPage.php");
    exit();
}
?>