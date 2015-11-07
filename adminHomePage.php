<?php

    require_once 'header.php';
    
?>

<html>
    <head>
        <link href="bootstrap.css" rel="stylesheet">
        <script src="jquery.min.js"></script>
        <script src="bootstrap.min.js"></script>
        
        <script type="text/javascript">
            window.onload = function(){
                alert("loaded");
            }
        </script>
    </head>
    <body>
        <div class="container-fluid">
        <?php 
            require_once 'truckLocations.php';
            require_once 'rateChange.php';
        ?>
        </div>
    </body>
</html>
