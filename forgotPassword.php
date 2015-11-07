<!DOCTYPE html>

<?php
require_once 'header.html';
?>

<html>
    <head>
        <link href="bootstrap.css" rel="stylesheet">
        <link href="one-page-wonder.css" rel="stylesheet">   
        <script src="formValidation.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-8 col-sm-4 col-md-4 col-sm-offset-4 col-md-offset-4">
                    <h1 align="center">Forgot Password</h1>
                    
                    
                    
                    <form method="POST" action="restorePassword.php" id="passwordRestore">
                        <!--Row 1 -->
                        <div class="form-group">
                            <input type="text" name="userName" id="userName" class="form-control input-lg" placeholder="User Name" tabindex="1">
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="form-group">
                            <input type="text" name="zipCode" id="zipCode" class="form-control input-lg" placeholder="Zip Code" tabindex="2">
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="form-group">
                            <input type="text" name="mobile" id="mobile" class="form-control input-lg" placeholder="Mobile" tabindex="3">
                        </div>
                        
                        <!-- Row 4 -->
                        <div class="row">
                            <input type="submit" value="Restore" class="btn btn-primary btn-block btn-lg" tabindex="4">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script src="jquery.min.js"/>
        <script src="bootstrap.min.js"/>
        
    </body>
</html>