<?php
    session_start();
    unset($_SESSION['userId']);
    unset($_SESSION['userName']);
    unset($_SESSION['userRole']);
    
    $message = $_GET['invalid'];
?>


<html>
    <head>
        <title>Login Page</title>
        <script src="formValidation.js"></script>

        <link href="bootstrap.css" rel="stylesheet"/>
        <link href="form.css" rel="stylesheet"/>
        

        <script>
            function formValidation(formId) {
                //alert(formId);
                var submit = validateMandatoryFields(formId);

                if (submit)
                    document.getElementById(formId).submit();
                else
                    alert("Please enter all the mandatory fields.");
            }
        </script>
        
        <style>
            .blue{
                background-image: url('blue.jpg');
            }
        </style>
    </head>

    <body style="font-family:verdana;" class='blue'>

        <?php include("header.html"); ?>

        <div class="container">
            <div class="card card-container">
                <?php if($message == true){
                ?>
                    <div class = "alert alert-danger" id="message">Warning ! Invalid Login credentials.</div>
                <?php
                }
                ?>
                
                
                <form class="form-signin" name="loginPage" id="loginPage" method="POST" action="validateLoginDetails.php">
                    <input type="text" id="userId" name="userId" class="form-control mandatory" placeholder="User Name" required autofocus>
                    <input type="password" id="userPassword" name="userPassword" class="form-control mandatory" placeholder="Password" required>

                    <button class="btn btn-lg btn-primary btn-block btn-signin" onclick="formValidation(this.form.id);">Sign in</button>
                </form>
                
                <a href="forgotPassword.php" class="forgot-password">
                    Forgot the password?
                </a>
                
                <br>

                <a href="registerUser.php" class="forgot-password" >New User?</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="registerUser.php?type=Driver" class="forgot-password">Register as Driver?</a>
            </div>
        </div>

        <script src="jquery.min.js"/>
        <script src="bootstrap.min.js"/>
    </body>
</html>
