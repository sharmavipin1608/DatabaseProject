<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    session_start();
    
    require_once('dbConnection.php');

    $userId = $_POST['userId'];
    $userPassword = $_POST['userPassword'];
    //echo $userId;
    //echo $userPassword;
    
    $query = "SELECT * FROM login where userId = '$userId' and password = '$userPassword';";
    //echo $query;
    
    $employee = mysql_query($query);
    
    $authenticate = mysql_fetch_array($employee);
    
    //echo sizeof($authenticate);
    
    if(sizeof($authenticate) > 1)
    {
        $userType = $authenticate['userType'];
        //echo "greater";
        $_SESSION['userId']=$authenticate['userId'];
        //$_SESSION['user_name']=$authenticate['FIRST_NAME'];
        $_SESSION['userRole']=$authenticate['userType'];
        
        if($userType == 'C')
            header("Location:customerHomePage.php");
        else if($userType == 'D')
            header("Location:driverHomePage.php");
        else if($userType == 'A')
            header("Location:adminHomePage.php");
        
        //header("Location:homePage.php");
        exit();
    }
    else
    {
        header("Location:loginPage.php?invalid=true");
    }

?>

