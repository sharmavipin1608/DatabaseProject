<?php
    require_once 'dbConnection.php';
    require_once 'header.html';
    
    $userName = $_POST['userName'];
    $zipCode = $_POST['zipCode'];
    $mobile = $_POST['mobile'];
    
    //echo $userName." : ".$zipCode." : ".$mobile;
    
    $query = "select password from login A,customer B where A.userId = B.userId "
            . "and A.userId = '$userName' and B.zipcode = '$zipCode' and "
            . "B.mobile = '$mobile'; ";
    $result = mysql_query($query);
    
    while ($row = mysql_fetch_array($result)) {
        $resultArray[] = $row;
    }
    
    foreach ($resultArray as $value) {
        $password = $value['password'];
    }
?>

<html>
    <head>
        <title>Trucks 4 U</title>
        <link href="bootstrap.css" rel="stylesheet">
    </head>
    
    <body>
        <div class="container">
        <?php
            if(sizeof($resultArray) > 0){
        ?>    
                <div id="forgotPwdAlert" class="alert alert-success">
                    Password is <strong><?php echo $password ?></strong>.
                        Click <a href="loginPage.php">here</a> to go back to Login Page.
                </div>
        <?php
            }
            else{
        ?>
                <div id="forgotPwdAlert" class="alert alert-danger">
                    Invalid details. Try again or 
                        click <a href="loginPage.php">here</a> to go back to Login Page.
                </div>
        <?php
            }
        ?>
        </div>
    </body>
</html>

    
    

