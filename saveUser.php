<?php
    require_once 'dbConnection.php';
    require_once 'header.html';
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = date('Y-m-d', strtotime($_POST['dob']));
    $streetName = $_POST['streetName'];
    $apartment = $_POST['apartment'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zipCode'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    $userType = 'C';
    
    if($_POST['userType'] == 'Driver'){
        $userType = 'D';
        $license = $_POST['license'];
        $registration = $_POST['registration'];
        $truckType = $_POST['truckType'];
        
        $driverQuery = "insert into driver values ('$userId','$registration','$truckType','$license');";
        //echo $driverQuery;
        mysql_query($driverQuery);
    }
    
    $customerQuery = "insert into customer values ('$userId','$firstName','$lastName'"
            . ",'$dob','$streetName','$apartment','$state','$zipCode',$mobile,'$email','$city');";
    
    //echo $customerQuery;
    
    $loginDetails = "insert into login values ('$userId','$password','$userType','A');";
    
    //echo $loginDetails;
    
    
    
    mysql_query($customerQuery);
    mysql_query($loginDetails);
    
    //echo $firstName." ".$lastName." ".$dob." ".$streetName." ".$apartment." ".$state.
            $zipCode." ".$email." ".$userId." ".$password;
    
    //echo date('Y-m-d', strtotime($dob));
?>

<html>
    <head>
        <title>Trucks 4 U</title>
        <link href="bootstrap.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
            <div id="forgotPwdAlert" class="alert alert-success">
                User <strong><?php echo $lastName.", ".$firstName; ?></strong> registered successfully.
                        Click <a href="loginPage.php">here</a> to go back to Login Page.
            </div>
        
        </div>
    </body>
</html>