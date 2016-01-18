<!DOCTYPE html>

<?php
require_once 'header.html';
?>
<html>
    <head>
        <link href="bootstrap.css" rel="stylesheet">
        <link href="one-page-wonder.css" rel="stylesheet">

        <script>
            function checkUserName(formId){
//                alert("checkUserName" + formId);
                var submit = true;
                var mandatoryFields = document.getElementsByClassName("mandatory");
                
                for (var i = 0; i < mandatoryFields.length; i++) {
                    var str = mandatoryFields[i].value.trim();
                    if (str== "") {
                        mandatoryFields[i].value = "";
                        submit = false;
                    }
                }
                
                if(!submit)
                    alert("Please enter all the mandatory fields before submit");
                else{
                    var ajaxRequest = new XMLHttpRequest();
                    var userName = document.getElementById("userId").value.trim();

                    ajaxRequest.onreadystatechange = function () {
                        if (ajaxRequest.readyState == 4) {
//                            alert(ajaxRequest.responseText);
//                            alert(ajaxRequest.responseText.indexOf("failure"));
                            if(ajaxRequest.responseText.indexOf("failure") >= 0)
                                document.getElementById('registerAlert').style.display = 'block';
                            else
                                document.getElementById(formId).submit();
                            //var ajaxDisplay = document.getElementById('release');
                            //document.getElementById('tripDetails').innerHTML = "Request withdrawn successfully";
                        }
                    }

                    ajaxRequest.open("GET", "queriesRepository.php?purpose=verifyUserName&userId="+userName, true);
                    ajaxRequest.send(null);
                }
            }
        </script>
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-xs-16 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">
                    <h1 align="center">Registration Page</h1>
                    <form method="POST" action="saveUser.php" id="registerUserForm">
                        <div id="registerAlert" class="alert alert-danger" style="display:none">
                            User name already exists. Please select a different user and try again.
                        </div>
                        
                        <!-- Row 1 -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="firstName" id="firstName" class="form-control input-lg mandatory" placeholder="First Name" tabindex="1">
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="lastName" id="lastName" class="form-control input-lg mandatory" placeholder="Last Name" tabindex="2">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="form-group">
                            <input type="date" name="dob" id="dob" class="form-control input-lg mandatory" placeholder="Date of Birth" tabindex="3">
                        </div>
                            
                        
                        <!--Row 3 -->
                        <div class="form-group">
                            <input type="text" name="streetName" id="streetName" class="form-control input-lg mandatory" placeholder="Address Line #1 (Street Name)" tabindex="6">
                        </div>
                        
                        <!-- Row 4 -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="apartment" id="apartment" class="form-control input-lg mandatory" placeholder="Address Line #2 (Apartment No)" tabindex="7">
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="city" id="city" class="form-control input-lg mandatory" placeholder="City" tabindex="8">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 5 -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="state" id="state" class="form-control input-lg mandatory" placeholder="State" tabindex="8">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="zipCode" id="zipCode" class="form-control input-lg mandatory" placeholder="Zip Code" tabindex="9">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 6 -->
                        <div class="form-group">
                            <input type="tel" name="mobile" id="mobile" class="form-control input-lg mandatory" placeholder="Mobile Number" tabindex="10">
                        </div>
                        
                        <!-- Row 7 -->
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control input-lg mandatory" placeholder="Email Address" tabindex="11">
                        </div>
                        
                        <hr>
                        
                        <!-- Section 3 -->
                        <!-- Row 9 -->
                        <div class="form-group">
                            <input type="text" name="userId" id="userId" class="form-control input-lg mandatory" placeholder="User Name" tabindex="12">
                        </div>
                        
                        <!-- Row 10 -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control input-lg mandatory" placeholder="Password" tabindex="13">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control input-lg mandatory" placeholder="Confirm Password" tabindex="14">
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <?php 
                        if($_GET['type']=='Driver'){
                        ?>
                        
                        <input type="hidden" name="userType" value="<?php echo $_GET['type']; ?>"/>
                        <!-- Section 2 -->
                        <!-- Row 8 -->
                        <i><span style="color:red">*This section only needs to be filled if you want to register as driver.</span></i>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <input type="text" name="license" id="license" class="form-control input-lg mandatory" placeholder="License No" tabindex="15">
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <input type="text" name="registration" id="registration" class="form-control input-lg mandatory" placeholder="Registration Number" tabindex="16">
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <select name="truckType" id="truckType" class="form-control input-lg mandatory" placeholder="Truck Type" tabindex="17">
                                        <option value="">Truck Type</option>
                                        <option value="S">Small</option>
                                        <option value="M">Medium</option>
                                        <option value="L">Large</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <?php
                        }
                        ?>
                        
                        <!-- Section 4 -->
                        <div class="row">
                            <input type="button" value="Register" class="btn btn-primary btn-block btn-lg" onclick="checkUserName(this.form.id)" tabindex="18">
                        </div>
                        
                        <br><br>
                    </form>
                </div>
            </div>
        </div>
    

    <script src="jquery.min.js"/>
    <script src="bootstrap.min.js"/>
</body>
</html>