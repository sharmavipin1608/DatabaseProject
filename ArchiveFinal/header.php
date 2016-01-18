<?php
    require_once 'authorizeUser.php';
?>

<html>
    <head>

    </head>
    <body style="font-family:verdana;">
        <table width="100%">
            <tr>
                <td width="34%"><img src="logofin.png" alt="Book Truck" style="width:200px;height:85px;"></td>
                <td width="33%" align="center"><h1>TRUCK BOOKING</h1></td>
                <td width="33%" align="right">
                    Hi <?php echo $_SESSION['userId'] ?> , <?php echo $_SESSION['userRole'] ?>
                    &nbsp;&nbsp;
                    <a href="homePage.php">Home</a>
                    &nbsp;&nbsp;
                    <a href="loginPage.php">Logout</a>
                </td>
            </tr>
        </table>
        
        <hr>
        
    </body>
</html>